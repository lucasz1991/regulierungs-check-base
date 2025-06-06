<?php

namespace App\Livewire\Likes;

use Livewire\Component;
use App\Models\Like;
use Illuminate\Support\Facades\Auth;

class LikeButton extends Component
{
    public $likeableType;
    public $likeableId;
    public string $size = 'md';

    public bool $liked = false;
    public int $likesCount = 0;

    public function mount(string $likeableType, int $likeableId, string $size = 'md')
    {
        $this->likeableType = $likeableType;
        $this->likeableId = $likeableId;
        $this->size = in_array($size, ['sm', 'md', 'lg']) ? $size : 'md';

        $this->updateLikeState();
    }

    public function toggle()
    {
        if (!Auth::check()) return;

        $like = Like::where([
            'user_id' => Auth::id(),
            'likeable_type' => $this->likeableType,
            'likeable_id' => $this->likeableId,
        ])->first();

        $like ? $like->delete() : Like::create([
            'user_id' => Auth::id(),
            'likeable_type' => $this->likeableType,
            'likeable_id' => $this->likeableId,
        ]);

        $this->updateLikeState();
    }

    protected function updateLikeState()
    {
        $this->likesCount = Like::where([
            'likeable_type' => $this->likeableType,
            'likeable_id' => $this->likeableId,
        ])->count();

        $this->liked = Auth::check() && Like::where([
            'user_id' => Auth::id(),
            'likeable_type' => $this->likeableType,
            'likeable_id' => $this->likeableId,
        ])->exists();
    }

    public function render()
    {
        return view('livewire.likes.like-button');
    }
}
