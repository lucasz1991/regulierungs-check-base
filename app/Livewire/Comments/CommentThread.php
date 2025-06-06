<?php

namespace App\Livewire\Comments;

use Livewire\Component;
use App\Models\Comment;
use Illuminate\Support\Facades\Auth;

class CommentThread extends Component
{
    public $commentableType;
    public $commentableId;
    public $body = '';
    public $depth;

    public function mount(string $commentableType, int $commentableId)
    {
        $this->commentableType = $commentableType;
        $this->commentableId = $commentableId;
    }

    public function save()
    {
        $this->validate([
            'body' => 'required|min:3',
        ]);

        Comment::create([
            'user_id' => Auth::id(),
            'body' => $this->body,
            'commentable_type' => $this->commentableType,
            'commentable_id' => $this->commentableId,
        ]);

        $this->reset('body');
    }

    public function render()
    {
        $comments = Comment::where('commentable_type', $this->commentableType)
            ->where('commentable_id', $this->commentableId)
            ->latest()
            ->get();

        return view('livewire.comments.comment-thread', [
            'comments' => $comments,
        ]);
    }
}
