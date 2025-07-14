<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Document;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PhpParser\Comment\Doc;

class CommentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if (!Auth::check()) {
            return redirect()->route('login')->with(['status' => 'error', 'message' => 'You must be logged in to create a comment.']);
        }

        try {
            $request->validate([
                'comment' => 'required|string|max:1000',
                'document_id' => 'required|exists:documents,id',
            ]);

            $comment = new Comment();
            $comment->comment = $request->comment;
            $comment->user_id = Auth::user()->id;
            $comment->document_id = $request->document_id;
            $comment->save();

            return redirect()->back()->with(['status' => 'success', 'message' => 'Comment created successfully.']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['status' => 'error', 'message' => 'Failed to create comment.' . $e->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Comment $comment)
    {
        if (Auth::id() !== $comment->user_id && !in_array(Auth::user()->role, ['admin', 'superadmin'])) {
            return redirect()->back()->with(['status' => 'error', 'message' => 'You are not authorized to delete this comment.']);
        }

        try {
            $comment->delete();
            return redirect()->back()->with(['status' => 'success', 'message' => 'Comment deleted successfully.']);
        } catch (\Exception $e) {
            return redirect()->back()->with(['status' => 'error', 'message' => 'Failed to delete comment: ' . $e->getMessage()]);
        }
    }
}
