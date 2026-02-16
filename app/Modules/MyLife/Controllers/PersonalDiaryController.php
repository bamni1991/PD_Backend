<?php

namespace App\Modules\MyLife\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\MyLife\Models\PersonalDiary;

class PersonalDiaryController extends Controller
{
    public function index(Request $request)
    {
        $query = PersonalDiary::orderBy('id', 'desc');

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $diaries = $query->paginate(10);
        return view('mylife::personal-diary.index', compact('diaries'));
    }

    public function exportPdf(Request $request)
    {
        $query = PersonalDiary::orderBy('id', 'desc');

        if ($request->has('search') && !empty($request->search)) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('content', 'like', "%{$search}%");
            });
        }

        $diaries = $query->get();
        return view('mylife::personal-diary.pdf', compact('diaries'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'content' => 'required',
            'entry_date' => 'required|date',
        ]);

        $diary = new PersonalDiary($request->only(['title', 'content', 'entry_date']));
        $diary->save();

        return redirect()->back()->with('success', 'Diary entry added successfully!');
    }
    
    public function update(Request $request, $id)
    {
         $diary = PersonalDiary::findOrFail($id);
         
         $request->validate([
            'content' => 'required',
            'entry_date' => 'required|date',
        ]);

        $diary->update($request->only(['title', 'content', 'entry_date']));

        return redirect()->back()->with('success', 'Diary entry updated successfully!');
    }

    public function destroy($id)
    {
        $diary = PersonalDiary::findOrFail($id);
        $diary->delete();

        return redirect()->back()->with('success', 'Diary entry deleted successfully!');
    }
}
