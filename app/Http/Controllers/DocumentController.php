<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentLog;
use App\Models\DocumentVersion;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Traits\HasRoles;

class DocumentController extends Controller
{
    use HasRoles;
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $documents = Document::where('status', 'unvalidated')->paginate(10);
        return view('documents.index', compact('documents'));
    }

    public function published()
    {
        $documents = Document::where('status', 'approved')->paginate(10);
        return view('documents.published', compact('documents'));
    }

    public function rejected()
    {
        $documents = Document::where('status', 'rejected')->paginate(10);
        return view('documents.rejected', compact('documents'));
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

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:5000',
            'file' => 'required|file|mimes:pdf, docx, xlsx, pptx|max:10240', // maks 10MB
        ]);

        try {
            $path = $request->file('file')->store('documents', 'public');

            $document = Document::create([
                'name' => $request->name,
                'current_version_id' => null,
                'description' => $request->description,
                'file_path' => null,
                'user_id' => Auth::user()->id,
            ]);

            $version = DocumentVersion::create([
                'document_id' => $document->id,
                'version_number' => 1,
                'file_path' => $path,
                'user_id' => Auth::user()->id,
            ]);

            $document->current_version_id = $version->id;
            $document->file_path = $version->file_path;
            $document->save();

            DocumentLog::create([
                'document_id' => $document->id,
                'user_id' => Auth::user()->id,
                'action' => 'create',
                'description' => 'Dokumen dibuat dengan versi 1'
            ]);

            return redirect()->route('documents.index')->with([
                'status' => 'success',
                'message' => 'Document created successfully.'
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return redirect()->route('documents.create')->with([
                'status' => 'error',
                'message' => 'Failed to create document: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Rollback document
     */
    public function rollback(Document $document) {
        if (Auth::id() !== $document->user_id) {
            return redirect()->route('documents.index')->with(['status' => 'error', 'message' => 'You are not authorized to edit this document.']);
        }

        $currentVersion = $document->currentVersion;
        $previousVersion = DocumentVersion::where('document_id', $document->id)
            ->where('version_number', '<', $currentVersion->version_number)
            ->orderBy('version_number', 'desc')
            ->first();

        if (!$previousVersion) {
            return redirect()->back()->with([
                'status' => 'error',
                'message' => 'No previous version available.'
            ]);
        }

        // Update ke versi sebelumnya
        $document->update([
            'current_version_id' => $previousVersion->id,
            'file_path' => $previousVersion->file_path
        ]);

        // Log activity
        DocumentLog::create([
            'document_id' => $document->id,
            'user_id' => Auth::id(),
            'action' => 'rollback_previous',
            'description' => "Rolled back from version {$currentVersion->version_number} to version {$previousVersion->version_number}"
        ]);

        return redirect()->back()->with([
            'status' => 'success',
            'message' => "Document rolled back to version {$previousVersion->version_number}"
        ]);
    }

    public function previous(Document $document) {
        $previousVersion = DocumentVersion::where('document_id', $document->id)
            ->where('version_number', '<', $document->currentVersion->version_number)
            ->orderBy('version_number', 'desc')
            ->get();

        if (!$previousVersion) {
            return redirect()->back()->with([
                'status' => 'error',
                'message' => 'No previous version available.'
            ]);
        }

        return view('documents.previous', compact('document', 'previousVersion'));
    }


    /**
     * Display the specified resource.
     */
    public function show(Document $document, $version = null)
    {
        $document->load(['user', 'comments.user']);
        $document->comments = $document->comments->sortByDesc('created_at');

        $document = Document::with('currentVersion')->findOrFail($document->id);

        return view('documents.show', compact('document'));
    }

    public function showPrevious(Document $document, $version = null)
    {
        if ($version !== null) {
            $version = DocumentVersion::where('document_id', $document->id)
                ->where('id', $version)
                ->first();

            if (!$version) {
                return redirect()->back()->with(['status' => 'error', 'message' => 'Version not found.']);
            }
        } else {
            // Default ke versi saat ini jika tidak ada parameter versi
            $version = $document->currentVersion;
        }

        $document->load(['user', 'comments.user', 'currentVersion']);
        $document->comments = $document->comments->sortByDesc('created_at');

        return view('documents.show-previous', compact('document', 'version'));
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
            $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:5000',
            'file' => 'nullable|file|mimes:pdf, docx, xlsx, pptx|max:10240', //max 10 MB
        ]);

        $path = $request->file('file')->store('documents', 'public');

            $currentVersion = DocumentVersion::find($document->current_version_id);
            $version = DocumentVersion::create([
                'document_id' => $document->id,
                'version_number' => $currentVersion->version_number + 1,
                'file_path' => $path,
                'user_id' => Auth::user()->id,
            ]);

            Document::where('id', $document->id)
                    ->update([
                        'name' => $request->name,
                        'description' => $request->description,
                        'current_version_id' => $version->id,
                        'file_path' => $path
                    ]);
                    
            //set approval to unvalidated
            $document->status = 'unvalidated';
            $document->reject_notes = null;
            $document->is_approvable = false;
            $document->save();
                    
            DocumentLog::create([
                'document_id' => $document->id,
                'user_id' => Auth::user()->id,
                'action' => 'update',
                'description' => 'Dokumen diperbarui dengan versi ' . $version->version_number
            ]);


            return redirect()->route('documents.index')->with([
                'status' => 'success',
                'message' => 'Document updated successfully.'
            ]);
        } catch (\Exception $e) {
            return redirect()->back()->withError($e->getMessage())->route('documents.edit', $document)->with(['status' => 'error', 'message' => 'Failed to update document.']);
        }
    }

    public function enableApproval(Document $document)
    {
        // Cek apakah user admin
        // if (!Auth::user()->hasRole('admin')) {
        //     abort(403);
        // }

        $document->is_approvable = true;
        $document->save();

        return back()->with('success', 'Approval diaktifkan untuk dokumen ini.');
    }

    public function disableApproval(Document $document)
    {
        // Cek apakah user admin
        // if (!Auth::user()->hasRole('admin')) {
        //     abort(403);
        // }

        $document->is_approvable = false;
        $document->save();

        return back()->with('success', 'Approval dinonaktifkan.');
    }



    public function approve(Document $document)
    {
        if ($document->status !== 'unvalidated') {
            return back()->with('error', 'Document has already been processed.');
        }

        $document->status = 'approved';
        $document->reject_notes = null;
        $document->is_approvable = false;
        $document->save();

        return redirect()->route('documents.published')->with('success', 'Document has been approved.');
    }

    public function reject(Request $request, Document $document)
    {
        if ($document->status !== 'unvalidated') {
            return back()->with('error', 'Document has already been processed.');
        }

        $request->validate([
            'reject_notes' => 'required|string|max:1000',
        ]);

        $document->status = 'rejected';
        $document->reject_notes = $request->reject_notes;
        $document->is_approvable = false;
        $document->save();

        return redirect()->route('documents.rejected')->with('success', 'Document has been rejected.');
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

    public function restore($id)
    {
        $document = Document::onlyTrashed()->findOrFail($id);
        $document->restore();
        return redirect()->route('documents.index')->with(['status' => 'success', 'message' => 'Document restored successfully.']);
    }

    public function forceDelete($id)
    {
        $document = Document::onlyTrashed()->findOrFail($id);

        if ($document->file_path && Storage::exists('storage/' . $document->file_path)) {
            Storage::delete('storage/' . $document->file_path);
        }

        $document->forceDelete();
        return redirect()->route('documents.index')->with(['status' => 'success', 'message' => 'Document deleted permanently.']);
    }
}
