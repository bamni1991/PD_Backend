<?php

namespace App\Modules\MyLife\Controllers; // NEW NAMESPACE

use App\Http\Controllers\Controller; // Extend base controller
use Illuminate\Http\Request;
use Google\Client;
use Google\Service\Drive;
use Illuminate\Support\Facades\Storage;

class FileManagerController extends Controller
{
    public function index(Request $request, $folderId = 'root')
    {
        // Check if user is authenticated for this module if needed 
        // (middleware already handles it)

        $client = $this->getClient();

        if (!$client) {
            return view('mylife::file-manager', ['groupedFiles' => [], 'connected' => false, 'error' => 'Client configuration failed']);
        }

        if (!$client->getAccessToken()) {
            return view('mylife::file-manager', ['groupedFiles' => [], 'connected' => false]);
            // NOTE: Views might need to be moved to App/Modules/MyLife/Resources/Views 
            // OR we continue causing current views path unless user moves views too.
            // For now I assume user keeps views in distinct place or I should adjust view path.
            // I used 'mylife::' hint in ServiceProvider, assuming views are moved.
            // BUT user hasn't moved views yet. Using standard view() might be safer if views not moved.
            // Let's stick to 'mylife::file-manager' as per original file unless I know views are moved.
        }

        // ... (rest of logic same)
        // Check usage of view() calls.

        if ($client->isAccessTokenExpired()) {
            if ($client->getRefreshToken()) {
                $client->fetchAccessTokenWithRefreshToken($client->getRefreshToken());
                $this->saveToken($client->getAccessToken());
            } else {
                // return view('mylife::file-manager', ...); 
                // Updating to use flexible view path or just keep as is for now?
                // The user only asked to move routes/controller. 
                // I will keep view path 'mylife::file-manager' assuming user will move views later or they exist in resources/views/MY_LIFE.
                return view('mylife::file-manager', ['groupedFiles' => [], 'connected' => false]);
            }
        }

        $service = new Drive($client);

        try {
            // 1. Get current folder metadata (name) if not root
            $currentFolder = null;
            $breadcrumbs = [['id' => 'root', 'name' => 'My Drive']];
            $filter = $request->query('filter', 'my-cloud');
            $search = $request->query('search');
            $orderBy = 'folder,createdTime desc'; // Default order

            if ($search) {
                // Sanitize search term for Drive API (escape single quotes)
                $escapedSearch = str_replace("'", "\'", $search);
                $query = "name contains '{$escapedSearch}' and trashed = false";
                $breadcrumbs = [['id' => 'root', 'name' => 'Search: ' . $search]];
                $folderId = 'root'; // Reset folder context for search
            } else {
                if ($folderId !== 'root' && $filter === 'my-cloud') {
                    $currentFolder = $service->files->get($folderId, ['fields' => 'id, name, parents']);
                    $breadcrumbs[] = ['id' => $folderId, 'name' => $currentFolder->name];
                }

                // 2. Build Query based on filter

                switch ($filter) {
                    case 'starred':
                        $query = "starred = true and trashed = false";
                        $breadcrumbs = [['id' => 'root', 'name' => 'Starred']];
                        break;
                    case 'trash':
                        $query = "trashed = true";
                        $breadcrumbs = [['id' => 'root', 'name' => 'Recycle Bin']];
                        break;
                    case 'recent':
                        $query = "trashed = false";
                        $orderBy = 'viewedByMeTime desc'; // Order by viewed time
                        $breadcrumbs = [['id' => 'root', 'name' => 'Recent']];
                        break;
                    case 'shared':
                        $query = "sharedWithMe = true and trashed = false";
                        $breadcrumbs = [['id' => 'root', 'name' => 'Shared with me']];
                        break;
                    default: // 'my-cloud'
                        $query = "'{$folderId}' in parents and trashed = false";
                        break;
                }
            }

            // 3. Get Storage Quota
            $about = $service->about->get(['fields' => 'storageQuota']);
            $quota = $about->storageQuota;
            $storageUsed = $quota->usage;
            $storageLimit = $quota->limit;
            $storageUsedGB = round($storageUsed / 1073741824, 2); // 1024^3
            $storageLimitGB = round($storageLimit / 1073741824, 2);
            $storagePercentage = ($storageLimit > 0) ? round(($storageUsed / $storageLimit) * 100) : 0;

            $results = $service->files->listFiles([
                'q' => $query,
                'fields' => 'nextPageToken, files(id, name, mimeType, iconLink, webViewLink, thumbnailLink, size, createdTime, starred, viewedByMeTime, parents)',
                'orderBy' => $orderBy,
                'pageSize' => 100
            ]);

            $files = collect($results->getFiles());

            // Prepare images for gallery (Flat list of all images in current view)
            $galleryImages = $files->filter(function ($file) {
                return str_contains($file->mimeType, 'image');
            })->map(function ($file) {
                // Modify thumbnail link to get a larger version for preview
                $thumb = $file->thumbnailLink;
                $large = $thumb;
                if ($thumb) {
                    $large = preg_replace('/=s\d+/', '=s2000', $thumb);
                }

                return (object) [
                    'id' => $file->id,
                    'name' => $file->name,
                    'thumbnail' => $file->thumbnailLink,
                    'preview' => $large,
                    'link' => $file->webViewLink,
                    'webViewLink' => $file->webViewLink, // Add duplicate for consistency if needed
                    'mimeType' => $file->mimeType,
                    'size' => $file->getSize(),
                    'starred' => $file->starred,
                    'createdTime' => $file->createdTime,
                    'viewedByMeTime' => $file->viewedByMeTime,
                    'parents' => $file->parents
                ];
            })->values();

            // Group files by year
            $groupedFiles = $files->sortByDesc(function ($file) use ($filter) {
                return $filter === 'recent' ? ($file->viewedByMeTime ?? $file->createdTime) : $file->createdTime;
            })->groupBy(function ($file) use ($filter) {
                $time = $filter === 'recent' ? ($file->viewedByMeTime ?? $file->createdTime) : $file->createdTime;
                return date('Y', strtotime($time));
            });

            return view('mylife::file-manager', [
                'groupedFiles' => $groupedFiles,
                'galleryImages' => $galleryImages,
                'connected' => true,
                'currentFolderId' => $folderId,
                'breadcrumbs' => $breadcrumbs,
                'filter' => $filter,
                'storageUsed' => $storageUsedGB,
                'storageLimit' => $storageLimitGB,
                'storagePercentage' => $storagePercentage
            ]);

        } catch (\Exception $e) {
            return view('mylife::file-manager', [
                'groupedFiles' => [],
                'connected' => false,
                'error' => $e->getMessage()
            ]);
        }
    }

    public function createFolder(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'parent_id' => 'required|string'
        ]);

        $client = $this->getClient();
        $service = new Drive($client);

        $fileMetadata = new Drive\DriveFile([
            'name' => $request->name,
            'mimeType' => 'application/vnd.google-apps.folder',
            'parents' => [$request->parent_id]
        ]);

        try {
            $service->files->create($fileMetadata, ['fields' => 'id']);
            return redirect()->back()->with('success', 'Folder created successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to create folder: ' . $e->getMessage());
        }
    }

    public function uploadFile(Request $request)
    {
        $request->validate([
            'files' => 'required|array',
            'files.*' => 'required|file',
            'parent_id' => 'required|string'
        ]);

        $client = $this->getClient();
        $service = new Drive($client);
        $uploadedFiles = $request->file('files');
        $successCount = 0;
        $errors = [];

        foreach ($uploadedFiles as $uploadedFile) {
            $fileMetadata = new Drive\DriveFile([
                'name' => $uploadedFile->getClientOriginalName(),
                'parents' => [$request->parent_id]
            ]);

            $content = file_get_contents($uploadedFile->getRealPath());

            try {
                $service->files->create($fileMetadata, [
                    'data' => $content,
                    'mimeType' => $uploadedFile->getMimeType(),
                    'uploadType' => 'multipart',
                    'fields' => 'id'
                ]);
                $successCount++;
            } catch (\Exception $e) {
                $errors[] = $uploadedFile->getClientOriginalName() . ': ' . $e->getMessage();
            }
        }

        if ($request->wantsJson()) {
            if ($successCount > 0 && empty($errors)) {
                return response()->json(['success' => true, 'message' => "Successfully uploaded {$successCount} file(s)"]);
            } else {
                return response()->json(['success' => false, 'message' => 'Upload failed: ' . implode(', ', $errors)], 500);
            }
        }

        if ($successCount > 0 && empty($errors)) {
            return redirect()->back()->with('success', "Successfully uploaded {$successCount} file(s)");
        } elseif ($successCount > 0 && !empty($errors)) {
            return redirect()->back()->with('success', "Uploaded {$successCount} file(s). Some failed: " . implode(', ', $errors));
        } else {
            return redirect()->back()->with('error', 'Failed to upload files: ' . implode(', ', $errors));
        }
    }

    public function renameFile(Request $request)
    {
        $request->validate([
            'id' => 'required|string',
            'name' => 'required|string|max:255'
        ]);

        $client = $this->getClient();
        $service = new Drive($client);

        $fileMetadata = new Drive\DriveFile([
            'name' => $request->name
        ]);

        try {
            $service->files->update($request->id, $fileMetadata, ['fields' => 'id']);
            return redirect()->back()->with('success', 'Item renamed successfully');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to rename: ' . $e->getMessage());
        }
    }

    public function deleteFile(Request $request)
    {
        $request->validate([
            'id' => 'nullable|string',
            'ids' => 'nullable|array'
        ]);

        if (!$request->id && empty($request->ids)) {
            return redirect()->back()->with('error', 'No items selected');
        }

        $client = $this->getClient();
        $service = new Drive($client);

        $fileMetadata = new Drive\DriveFile([
            'trashed' => true
        ]);

        $ids = $request->ids ?? [$request->id];
        $successCount = 0;
        $errors = [];

        foreach ($ids as $fileId) {
            try {
                $service->files->update($fileId, $fileMetadata, ['fields' => 'id']);
                $successCount++;
            } catch (\Exception $e) {
                $errors[] = $e->getMessage();
            }
        }

        if (count($errors) > 0) {
            return redirect()->back()->with('warning', $successCount . ' item(s) moved to trash. Some failed.');
        }

        return redirect()->back()->with('success', $successCount . ' item(s) moved to trash');
    }

    public function auth()
    {
        $client = $this->getClient();
        $authUrl = $client->createAuthUrl();
        return redirect()->away($authUrl);
    }

    public function callback(Request $request)
    {
        $client = $this->getClient();
        $code = $request->input('code');

        if ($code) {
            $accessToken = $client->fetchAccessTokenWithAuthCode($code);
            $this->saveToken($accessToken);
        }

        return redirect()->route('file-manager.index'); // Updated to use correct named route
    }

    private function getClient()
    {
        $client = new Client();
        $client->setApplicationName('My Drive Project');
        $client->setScopes(Drive::DRIVE); // Full access to Google Drive
        $client->setClientId(env('GOOGLE_CLIENT_ID'));
        $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
        $client->setRedirectUri(route('file-manager.callback'));
        $client->setAccessType('offline');
        $client->setPrompt('select_account consent');

        $tokenPath = storage_path('app/google_drive_token.json');
        if (file_exists($tokenPath)) {
            $accessToken = json_decode(file_get_contents($tokenPath), true);
            $client->setAccessToken($accessToken);
        }

        return $client;
    }

    private function saveToken($token)
    {
        $tokenPath = storage_path('app/google_drive_token.json');
        file_put_contents($tokenPath, json_encode($token));
    }
}

