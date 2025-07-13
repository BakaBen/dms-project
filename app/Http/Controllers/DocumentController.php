<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $documents = Document::paginate(10);
        return view('documents.index', compact('documents'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        return view('documents.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with(['status' => 'error', 'message' => 'You must be logged in to create a document.']);
        }

        $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:5000',
            'file' => 'required|file|mimes:pdf,docx,xlsx,pptx,zip|max:10240', //max 10 MB
        ]);

        $validatedData['file'] = $request->file('file')->store('documents', 'public');

        try {
            $document = new Document();
            $document->name = $validatedData['name'];
            $document->description = $validatedData['description'];
            $document->user_id = Auth::user()->id;
            $document->file_path = $validatedData['file']; // Simpan path file
            $document->save();

            return redirect()->route('documents.index')->with(['status' => 'success', 'message' => 'Document created successfully.']);
        } catch (\Exception $e) {
            return redirect()->route('documents.create')->with(['status' => 'error', 'message' => 'Failed to create document: ' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Document $document)
    {
        $document->load(['user', 'comments.user']);
        $document->comments = $document->comments->sortByDesc('created_at');

        return view('documents.show', compact('document'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Document $document)
    {
        if (Auth::id() !== $document->user_id) {
            return redirect()->route('documents.index')->with(['status' => 'error', 'message' => 'You are not authorized to edit this document.']);
        }

        return view('documents.edit', compact('document'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Document $document)
    {
        if (Auth::id() !== $document->user_id) {
            return redirect()->route('documents.index')->with(['status' => 'error', 'message' => 'You are not authorized to edit this document.']);
        }

        try {
            $validatedData = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:5000',
            'file' => 'nullable|file|mimes:pdf,docx,xlsx,pptx,zip|max:10240', //max 10 MB
        ]);

        if ($request->hasFile('file')) {
            if($document->file_path && Storage::disk('public')->exists($document->file_path)){
                Storage::disk('public')->delete($document->file_path);
            }
            $validatedData['file'] = $request->file('file')->store('documents', 'public');
        }

        $document->name = $validatedData['name'];
        $document->description = $validatedData['description'];
        $document->file_path = $validatedData['file'] ?? $document->file_path;
        $document->save();

        return redirect()->route('documents.index')->with(['status' => 'success', 'message' => 'Document updated successfully.']);
        } catch (\Exception $e) {
            return redirect()->withError($e->getMessage())->route('documents.edit', $document)->with(['status' => 'error', 'message' => 'Failed to update document.']);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Document $document)
    {
        if (Auth::id() !== $document->user_id && !in_array(Auth::user()->role, ['admin', 'superadmin'])) {
            return redirect()->route('documents.index')->with(['status' => 'error', 'message' => 'You are not authorized to delete this document.']);
        }

        $document->delete();

        return redirect()->route('documents.index')->with(['status' => 'success', 'message' => 'Document deleted successfully.']);
    }

    public function trashed(Request $request)
    {
        $documents = Document::onlyTrashed()->latest()->paginate(10);
        return view('documents.trashed', compact('documents'));
    }

    public function restore(Document $document)
    {
        $document->restore();
        return redirect()->route('documents.index')->with(['status' => 'success', 'message' => 'Document restored successfully.']);
    }

    public function forceDelete(Document $document)
    {
        $document->forceDelete();
        return redirect()->route('documents.index')->with(['status' => 'success', 'message' => 'Document deleted permanently.']);
    }
}
