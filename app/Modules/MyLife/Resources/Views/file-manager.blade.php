@extends('ui.layout')

@section('title', 'File Manager')

@section('content')
    <!-- Global Loading Overlay -->
    <div id="global-loader"
        style="display:none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; z-index: 9999; align-items: center; justify-content: center; flex-direction: column; backdrop-filter: blur(4px); background: rgba(255,255,255,0.7);">
        <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem; border-width: 3px;">
            <span class="visually-hidden">Loading...</span>
        </div>
        <div class="fw-bold text-dark mt-3" style="font-size: 1rem; letter-spacing: 0.5px;">Loading...</div>
    </div>

    <div class="container-fluid">
        <!-- Toast / Alerts -->
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
                <div class="d-flex align-items-center">
                    <i class="ti ti-check-circle me-2 fs-5"></i>
                    <div class="fw-medium">{{ session('success') }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif
        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm rounded-3 mb-4" role="alert">
                <div class="d-flex align-items-center">
                    <i class="ti ti-alert-circle me-2 fs-5"></i>
                    <div class="fw-medium">{{ session('error') }}</div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        <!-- Breadcrumb start -->
        <div class="row m-1">
            <div class="col-12 ">
                <h4 class="main-title">
                    {{ ($currentFolderId ?? '') === 'root' ? 'My Cloud' : (isset($breadcrumbs) && count($breadcrumbs) > 0 ? end($breadcrumbs)['name'] : 'File Manager') }}
                </h4>
                <ul class="app-line-breadcrumbs mb-3">
                    <li class="{{ !isset($breadcrumbs) || count($breadcrumbs) === 0 ? 'active' : '' }}">
                        <a class="f-s-14 f-w-500" href="{{ route('file-manager.index') }}" onclick="showLoader()">
                            <span>
                                <i class="ph-duotone ph-cloud f-s-16"></i> My Cloud
                            </span>
                        </a>
                    </li>
                    @if (isset($breadcrumbs))
                        @foreach ($breadcrumbs as $crumb)
                            <li class="{{ $loop->last ? 'active' : '' }}">
                                <a class="f-s-14 f-w-500"
                                    href="{{ $crumb['id'] === 'root' ? route('file-manager.index') : route('file-manager.folder', $crumb['id']) }}"
                                    onclick="showLoader()">
                                    {{ $crumb['name'] }}
                                </a>
                            </li>
                        @endforeach
                    @endif
                </ul>
            </div>
        </div>
        <!-- Breadcrumb end -->

        <div class="row">
            <!-- Sidebar -->
            <div class="col-lg-3 d-none d-lg-block">
                <div class="card">
                    <div class="card-header">
                        <h5>My Drive</h5>
                    </div>
                    <div class="card-body">
                        <div class="horizontal-tab-wrapper">
                            @if (isset($connected) && $connected)
                                <div class="d-grid mb-3">
                                    <button class="btn btn-primary" type="button" data-bs-toggle="dropdown"
                                        aria-expanded="false">
                                        <i class="ti ti-plus me-2 fs-5"></i> New
                                    </button>
                                    <ul class="dropdown-menu w-100">
                                        <li>
                                            <a class="dropdown-item py-2" href="#" data-bs-toggle="modal"
                                                data-bs-target="#createFolderModal">
                                                <i class="ti ti-folder-plus me-2 text-warning fs-5"></i> New Folder
                                            </a>
                                        </li>
                                        <li>
                                            <hr class="dropdown-divider my-1">
                                        </li>
                                        <li>
                                            <a class="dropdown-item py-2" href="#" data-bs-toggle="modal"
                                                data-bs-target="#uploadFileModal">
                                                <i class="ti ti-upload me-2 text-primary fs-5"></i> Upload File
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                            @endif

                            <ul class="filemenu-list mt-3 tabs">
                                <li class="tab-link {{ ($filter ?? 'my-cloud') === 'my-cloud' ? 'active' : '' }}">
                                    <a href="{{ route('file-manager.index', ['filter' => 'my-cloud']) }}" onclick="showLoader()"
                                        class="d-flex align-items-center w-100 text-inherit">
                                        <i class="ti ti-cloud-filled fs-5 pe-2"></i> <span class="flex-grow-1">My
                                            Cloud</span>
                                    </a>
                                </li>
                                <li class="tab-link {{ ($filter ?? '') === 'starred' ? 'active' : '' }}">
                                    <a href="{{ route('file-manager.index', ['filter' => 'starred']) }}" onclick="showLoader()"
                                        class="d-flex align-items-center w-100 text-inherit">
                                        <i class="ti ti-star-filled fs-5 pe-2"></i> <span class="flex-grow-1">Starred</span>
                                    </a>
                                </li>
                                <li class="tab-link {{ ($filter ?? '') === 'recent' ? 'active' : '' }}">
                                    <a href="{{ route('file-manager.index', ['filter' => 'recent']) }}" onclick="showLoader()"
                                        class="d-flex align-items-center w-100 text-inherit">
                                        <i class="ti ti-clock-filled fs-5 pe-2"></i> <span class="flex-grow-1">Recent</span>
                                    </a>
                                </li>
                                <li class="tab-link {{ ($filter ?? '') === 'shared' ? 'active' : '' }}">
                                    <a href="{{ route('file-manager.index', ['filter' => 'shared']) }}" onclick="showLoader()"
                                        class="d-flex align-items-center w-100 text-inherit">
                                        <i class="ti ti-users-filled fs-5 pe-2"></i> <span class="flex-grow-1">Shared</span>
                                    </a>
                                </li>
                                <li class="tab-link {{ ($filter ?? '') === 'trash' ? 'active' : '' }}">
                                    <a href="{{ route('file-manager.index', ['filter' => 'trash']) }}" onclick="showLoader()"
                                        class="d-flex align-items-center w-100 text-inherit">
                                        <i class="ti ti-trash-filled fs-5 pe-2"></i> <span class="flex-grow-1">Trash</span>
                                    </a>
                                </li>
                                <li class="app-divider-v dashed p-0 m-2"></li>
                                <li class="tab-link">
                                    <a href="#" class="d-flex align-items-center w-100 text-inherit"
                                        data-bs-toggle="modal" data-bs-target="#settingsModal">
                                        <i class="ti ti-settings fs-5 pe-2"></i> <span class="flex-grow-1">Settings</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                @if (isset($storageUsed) && isset($storageLimit))
                    <div class="card">
                        <div class="card-header">
                            <h5>Storage</h5>
                        </div>
                        <div class="card-body">
                            <div class="mb-3">
                                <h6 class="mb-1">{{ $storagePercentage }}% Used</h6>
                                <div class="progress h-5">
                                    <div class="progress-bar bg-primary h-5" role="progressbar"
                                        style="width: {{ $storagePercentage }}%;"></div>
                                </div>
                                <p class="text-secondary mt-1 f-s-12">{{ $storageUsed }} GB of {{ $storageLimit }} GB
                                    used</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- Content -->
            <div class="col-lg-9">
                <div class="mb-3 d-lg-none">
                    @if (isset($connected) && $connected)
                        <div class="d-flex gap-2">
                            <button class="btn btn-primary flex-grow-1" data-bs-toggle="modal"
                                data-bs-target="#createFolderModal"> New Folder</button>
                            <button class="btn btn-secondary flex-grow-1" data-bs-toggle="modal"
                                data-bs-target="#uploadFileModal"> Upload File</button>
                        </div>
                    @endif
                </div>

                @if (isset($connected) && $connected)
                    <div class="row m-1 mb-3">
                        <div class="col-12">
                            <form action="{{ route('file-manager.index') }}" method="GET"
                                class="app-form app-icon-form w-100" onsubmit="showLoader()">
                                <div class="position-relative">
                                    <input type="search" name="search" class="form-control search-filter"
                                        placeholder="Search files, folders..." value="{{ request('search') }}">
                                    <i class="ti ti-search text-dark"></i>
                                </div>
                            </form>
                        </div>
                    </div>

                    @if (isset($groupedFiles) && count($groupedFiles) > 0)
                        @foreach ($groupedFiles as $year => $files)
                            <div class="card mb-4 documents-section">
                                <div class="card-header d-flex justify-content-between align-items-center py-2">
                                    <h5 class="mb-0">{{ $year === date('Y') ? 'Recent Files' : $year }}
                                        <span class="text-muted ms-2 f-s-14">({{ count($files) }} items)</span>
                                    </h5>
                                </div>
                                <div class="card-body p-0">
                                    <div class="table-responsive">
                                        <table
                                            class="table table-bottom-border recent-table align-middle table-hover mb-0">
                                            <thead>
                                                <tr>
                                                    <th scope="col" class="ps-3">Name</th>
                                                    <th scope="col">Size</th>
                                                    <th scope="col">Last Modified</th>
                                                    <th scope="col">Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($files as $file)
                                                    @php
                                                        $isFolder =
                                                            $file->mimeType == 'application/vnd.google-apps.folder';
                                                        $isImage = str_contains($file->mimeType, 'image');

                                                        if ($isFolder) {
                                                            $link = route('file-manager.folder', $file->id);
                                                            $onclick = "showLoader(); window.location.href='$link'";
                                                            $iconImg = asset('assets/images/icons/folder.png');
                                                        } elseif ($isImage) {
                                                            $onclick = "openGallery('" . $file->id . "')";
                                                            $iconImg = asset('assets/images/icons/gallary.png');
                                                        } elseif (str_contains($file->mimeType, 'pdf')) {
                                                            $onclick = "window.open('{$file->webViewLink}', '_blank')";
                                                            $iconImg = asset('assets/images/icons/pdf.png');
                                                        } elseif (
                                                            str_contains($file->mimeType, 'sheet') ||
                                                            str_contains($file->mimeType, 'excel')
                                                        ) {
                                                            $onclick = "window.open('{$file->webViewLink}', '_blank')";
                                                            $iconImg = asset('assets/images/icons/file.png');
                                                        } else {
                                                            $link = $file->webViewLink;
                                                            $onclick = "window.open('$link', '_blank')";
                                                            $iconImg = asset('assets/images/icons/file.png');
                                                        }
                                                    @endphp

                                                    <tr class="cursor-pointer"
                                                        onclick="if(!event.target.closest('.dropdown') && !event.target.closest('.fav-icon')) { {!! $onclick !!} }"
                                                        style="cursor: pointer;">
                                                        <td class="ps-3">
                                                            <div class="d-flex align-items-center">
                                                                @if ($isImage && isset($file->thumbnailLink))
                                                                    <img src="{{ $file->thumbnailLink }}" alt="icon"
                                                                        class="w-20 h-20 rounded"
                                                                        style="object-fit: cover;">
                                                                @else
                                                                    <img src="{{ $iconImg }}" alt="icon"
                                                                        class="w-20 h-20">
                                                                @endif
                                                                <span class="ms-2 table-text text-truncate"
                                                                    style="max-width: 250px;"
                                                                    title="{{ $file->name }}">{{ $file->name }}</span>
                                                            </div>
                                                        </td>
                                                        <td class="text-secondary f-w-500">
                                                            @if (!$isFolder && isset($file->size))
                                                                {{ number_format($file->size / 1024, 0) }} KB
                                                            @else
                                                                -
                                                            @endif
                                                        </td>
                                                        <td class="text-secondary f-w-500">
                                                            {{ \Carbon\Carbon::parse($file->createdTime)->format('d M, Y') }}
                                                        </td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="dropdown folder-dropdown me-2">
                                                                    <a aria-expanded="false" class=""
                                                                        data-bs-toggle="dropdown" role="button">
                                                                        <i class="ti ti-dots-vertical text-secondary"></i>
                                                                    </a>
                                                                    <ul class="dropdown-menu">
                                                                        @if ($isFolder)
                                                                            <li><a class="dropdown-item"
                                                                                    href="{{ route('file-manager.folder', $file->id) }}"
                                                                                    onclick="showLoader()"><i
                                                                                        class="ti ti-folder-open text-primary me-2"></i>
                                                                                    Open</a></li>
                                                                        @elseif ($isImage)
                                                                            <li><a class="dropdown-item" href="#"
                                                                                    onclick="openGallery('{{ $file->id }}')"><i
                                                                                        class="ti ti-eye text-primary me-2"></i>
                                                                                    Preview</a></li>
                                                                        @else
                                                                            <li><a class="dropdown-item"
                                                                                    href="{{ $file->webViewLink }}"
                                                                                    target="_blank"><i
                                                                                        class="ti ti-eye text-primary me-2"></i>
                                                                                    View</a></li>
                                                                        @endif
                                                                        <li><a class="dropdown-item" href="#"
                                                                                data-bs-toggle="modal"
                                                                                data-bs-target="#renameModal"
                                                                                onclick="setRenameData('{{ $file->id }}', '{{ $file->name }}');"><i
                                                                                    class="ti ti-edit text-success me-2"></i>
                                                                                Rename</a></li>
                                                                        <li><a class="dropdown-item delete-btn"
                                                                                href="#" data-bs-toggle="modal"
                                                                                data-bs-target="#deleteModal"
                                                                                onclick="setDeleteData('{{ $file->id }}');"><i
                                                                                    class="ti ti-trash text-danger me-2"></i>
                                                                                Delete</a></li>
                                                                    </ul>
                                                                </div>
                                                                <div class="starreddiv favBtn">
                                                                    @if ($file->starred ?? false)
                                                                        <i
                                                                            class="ph-fill ph-star text-warning f-s-18 fav-icon"></i>
                                                                    @else
                                                                        <i
                                                                            class="ph-bold ph-star text-secondary f-s-18 fav-icon"></i>
                                                                    @endif
                                                                </div>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @else
                        <!-- Empty State -->
                        <div class="card">
                            <div class="card-body text-center p-5">
                                <img src="{{ asset('assets/images/icons/folder.png') }}" alt="Empty" class="mb-3"
                                    style="width: 80px; opacity: 0.5;">
                                <h4>No files found</h4>
                                <p class="text-muted">Upload a file or create a folder to get started.</p>
                                <button class="btn btn-primary" data-bs-toggle="modal"
                                    data-bs-target="#uploadFileModal">Upload File</button>
                            </div>
                        </div>
                    @endif
                @else
                    <!-- Not Connected -->
                    <div class="d-flex align-items-center justify-content-center" style="min-height: 50vh;">
                        <div class="card border-0 shadow-lg rounded-4 overflow-hidden"
                            style="max-width: 480px; width: 100%;">
                            <div class="card-body text-center p-5">
                                <div class="mb-4">
                                    <i class="fab fa-google-drive" style="font-size: 3rem; color: #4285f4;"></i>
                                </div>
                                <h3 class="fw-bold mb-2">Connect Google Drive</h3>
                                <p class="text-muted mb-4 mx-auto">Link your Google account to access your files.</p>
                                @if (isset($error))
                                    <div class="alert alert-danger mx-auto mb-4 text-start font-small">{{ $error }}
                                    </div>
                                @endif
                                <a href="{{ route('file-manager.auth') }}"
                                    class="btn btn-primary px-5 py-3 rounded-pill shadow-lg w-100 fw-bold">Connect Google
                                    Account</a>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- MODALS --}}

    <!-- Image Preview Modal -->
    <div class="modal fade" id="imagePreviewModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static">
        <div class="modal-dialog modal-fullscreen">
            <div class="modal-content border-0 bg-dark">
                <div class="position-absolute top-0 end-0 p-4" style="z-index: 20;">
                    <button type="button" class="btn btn-dark rounded-circle p-2" data-bs-dismiss="modal"
                        aria-label="Close"><i class="ti ti-x fs-4"></i></button>
                </div>
                <div class="modal-body p-0 d-flex flex-column justify-content-center align-items-center position-relative">
                    <img id="previewImage" src="" alt="Preview"
                        style="max-height: 90vh; max-width: 90vw; object-fit: contain; transition: transform 0.2s;">
                    <button class="preview-nav preview-prev position-absolute start-0 ms-4 btn btn-dark rounded-circle p-3"
                        onclick="changeImage(-1)">
                        <i class="ti ti-chevron-left fs-4"></i>
                    </button>
                    <button class="preview-nav preview-next position-absolute end-0 me-4 btn btn-dark rounded-circle p-3"
                        onclick="changeImage(1)">
                        <i class="ti ti-chevron-right fs-4"></i>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Create Folder Modal -->
    <div class="modal fade" id="createFolderModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white">New Folder</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('file-manager.create-folder') }}" method="POST" onsubmit="showLoader()">
                    @csrf
                    <input type="hidden" name="parent_id" value="{{ $currentFolderId ?? 'root' }}">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Folder Name</label>
                            <input type="text" name="name" class="form-control" placeholder="Folder Name" required
                                autofocus>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-light-primary">Create</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Upload File Modal -->
    <div class="modal fade" id="uploadFileModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white">Upload Files</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="uploadForm" action="{{ route('file-manager.upload') }}" method="POST"
                    enctype="multipart/form-data" onsubmit="handleUpload(event)">
                    @csrf
                    <input type="hidden" name="parent_id" value="{{ $currentFolderId ?? 'root' }}">
                    <div class="modal-body">
                        <div class="mb-3 text-center p-4 border border-dashed rounded bg-light"
                            onclick="document.getElementById('fileInput').click()" style="cursor: pointer;">
                            <i class="ti ti-cloud-upload fs-1 text-primary mb-2"></i>
                            <p class="text-muted mb-0" id="custom-text">Click to select files</p>
                            <input type="file" name="files[]" id="fileInput" multiple hidden
                                onchange="updateFileCount()">
                        </div>
                        <div id="upload-progress-container" class="mt-3"
                            style="display: none; max-height: 150px; overflow-y: auto;"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-light-primary">Upload</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Rename Modal -->
    <div class="modal fade" id="renameModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header bg-primary">
                    <h5 class="modal-title text-white">Rename</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('file-manager.rename') }}" method="POST" onsubmit="showLoader()">
                    @csrf
                    <input type="hidden" name="id" id="rename_id">
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">New Name</label>
                            <input type="text" name="name" id="rename_name" class="form-control" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-light-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-body text-center p-4">
                    <img src="{{ asset('assets/images/icons/delete-icon.png') }}" class="img-fluid mb-3" alt="Delete"
                        style="max-width: 100px;">
                    <h4 class="text-danger fw-bold">Are you sure?</h4>
                    <p class="text-secondary">You won't be able to revert this!</p>
                    <form action="{{ route('file-manager.delete') }}" method="POST" onsubmit="showLoader()">
                        @csrf
                        <input type="hidden" name="id" id="delete_id">
                        <div class="mt-4">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-light-primary">Yes, Delete it</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Settings Modal -->
    <div class="modal fade" id="settingsModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Settings</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex align-items-center justify-content-between p-3 rounded bg-light border">
                        <div class="d-flex align-items-center">
                            <i class="fab fa-google-drive text-primary fs-3 me-3"></i>
                            <div>
                                <h6 class="mb-0 fw-bold">Google Drive</h6>
                                <small class="text-success fw-medium">Connected</small>
                            </div>
                        </div>
                    </div>
                    <div class="text-center mt-3">
                        <p class="text-muted small">File Manager v1.0.3 (Compact Mode)</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            // Gallery Logic
            const galleryImages = @json($galleryImages ?? []);
            let currentImageIndex = -1;

            function openGallery(fileId) {
                const index = galleryImages.findIndex(img => img.id === fileId);
                if (index !== -1) {
                    currentImageIndex = index;
                    showImage(index);
                    const modal = new bootstrap.Modal(document.getElementById('imagePreviewModal'));
                    modal.show();
                }
            }

            function showImage(index) {
                if (index < 0 || index >= galleryImages.length) return;
                const imgData = galleryImages[index];
                const imgElement = document.getElementById('previewImage');
                imgElement.src = imgData.preview;
                const prevBtn = document.querySelector('.preview-prev');
                const nextBtn = document.querySelector('.preview-next');
                if (prevBtn) prevBtn.disabled = index === 0;
                if (nextBtn) nextBtn.disabled = index === galleryImages.length - 1;
            }

            function changeImage(direction) {
                const newIndex = currentImageIndex + direction;
                if (newIndex >= 0 && newIndex < galleryImages.length) {
                    currentImageIndex = newIndex;
                    showImage(newIndex);
                }
            }

            function showLoader() {
                const loader = document.getElementById('global-loader');
                if (loader) loader.style.display = 'flex';
            }

            function setRenameData(id, name) {
                document.getElementById('rename_id').value = id;
                document.getElementById('rename_name').value = name;
            }

            function setDeleteData(id) {
                document.getElementById('delete_id').value = id;
            }

            // Functions for upload
            function updateFileCount() {
                const fileInput = document.getElementById('fileInput');
                const customText = document.getElementById('custom-text');
                if (fileInput.files.length > 0) {
                    customText.innerText = `${fileInput.files.length} files selected`;
                } else {
                    customText.innerText = 'Click to select files';
                }
            }

            async function handleUpload(e) {
                e.preventDefault();
                const form = document.getElementById('uploadForm');
                const parentId = form.querySelector('input[name="parent_id"]').value;
                const files = document.getElementById('fileInput').files;
                if (files.length === 0) return;

                const progressContainer = document.getElementById('upload-progress-container');
                progressContainer.style.display = 'block';
                progressContainer.innerHTML = '';
                const submitBtn = form.querySelector('button[type="submit"]');
                submitBtn.disabled = true;
                submitBtn.innerText = 'Uploading...';

                for (let i = 0; i < files.length; i++) {
                    const file = files[i];
                    const div = document.createElement('div');
                    div.className = 'mb-2';
                    div.innerHTML =
                        `<small>${file.name}</small><div class="progress" style="height:5px"><div class="progress-bar bg-primary" style="width:0%"></div></div>`;
                    progressContainer.appendChild(div);
                    const progressBar = div.querySelector('.progress-bar');

                    // Simulate upload or actual logic (simplified to serial for now)
                    await uploadFile(file, parentId, progressBar);
                }
                location.reload();
            }

            function uploadFile(file, parentId, progressBar) {
                return new Promise((resolve, reject) => {
                    const formData = new FormData();
                    formData.append('files[]', file);
                    formData.append('parent_id', parentId);
                    const xhr = new XMLHttpRequest();
                    xhr.open('POST', '{{ route('file-manager.upload') }}', true);
                    xhr.setRequestHeader('X-CSRF-TOKEN', '{{ csrf_token() }}');

                    xhr.upload.onprogress = function(e) {
                        if (e.lengthComputable) {
                            const percent = (e.loaded / e.total) * 100;
                            progressBar.style.width = percent + '%';
                        }
                    };
                    xhr.onload = function() {
                        if (xhr.status === 200) resolve();
                        else reject();
                    };
                    xhr.onerror = reject;
                    xhr.send(formData);
                });
            }

            // Expose globally
            window.openGallery = openGallery;
            window.changeImage = changeImage;
            window.showLoader = showLoader;
            window.setRenameData = setRenameData;
            window.setDeleteData = setDeleteData;
            window.updateFileCount = updateFileCount;
            window.handleUpload = handleUpload;
        </script>
    @endpush
@endsection


