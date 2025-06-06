<div  x-data="{ depth: @entangle('depth') }" class=" max-w-2xl">
    {{-- Kommentarformular --}}
    @auth
        <div x-data="{ showForm: false }" x-init="showForm = depth == 1" class="">
            {{-- Button zum Öffnen --}}
            <div x-show="!showForm" x-collapse x-cloak>
                <button @click="showForm = true"
                        class="text-xs" :class="depth === 1 ? 'text-white p-2 rounded bg-teal-600 hover:bg-teal-700  mb-6' : 'text-green-600  mb-2'">
                    <span x-text="depth === 1 ? 'Kommentieren' : 'Antworten'"></span>
                </button>
            </div>
            {{-- Kommentarformular --}}
            <div x-show="showForm" @click.away="showForm = false" x-collapse x-cloak>
                <div class="mb-6">
                    <form wire:submit.prevent="save" class="flex justify-stretch gap-0 shadow rounded-xl">
                        <textarea wire:model.defer="body"
                        rows="1"
                        class="w-full border border-gray-300 text-base p-2 rounded-s-xl"
                        placeholder="Dein Kommentar..."></textarea>
                        <button type="submit" class="bg-teal-600 hover:bg-teal-700 text-sm text-white px-4 py-2 rounded-e-xl">
                            Senden
                        </button>
                    </form>
                    @error('body')
                        <div class="text-sm text-red-600 mt-1">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>
    @endauth
    {{-- Kommentarauflistung --}}
    <div class="">
        @forelse($comments as $comment)
            <div class="border border-gray-200 p-3 pb-0 rounded-xl bg-white mb-2">
                <div class="text-sm text-gray-600 mb-1 flex items-center justify-between">
                    <div class=" flex items-center ">
                        <x-user.public-info :user="$comment->user" context="comments" />
                        <span class="text-xs text-gray-500 h-min ml-2">
                             - {{ $comment->created_at->diffForHumans() }}
                        </span>

                    </div>

                    <livewire:likes.like-button
                        :likeable-type="\App\Models\Comment::class"
                        :likeable-id="$comment->id"
                        size="sm"
                        :wire:key="'like-comment-'.$comment->id"
                    />
                    
                </div>
                <hr class="my-2">
                <div class="text-gray-800 mb-4 px-4">{{ $comment->body }}</div>
            </div>
            <div class="pl-8">
                @if($depth < 2)
                    <livewire:comments.comment-thread 
                        :commentable-type="\App\Models\Comment::class"
                        :commentable-id="$comment->id"
                        :depth="$depth + 1"
                        :key="'comment-'.$comment->id"
                    />
                @endif
            </div>
        @empty
            <span class="text-gray-400 text-sm"  x-text="depth === 1 ? 'Noch keine Kommentare vorhanden' : ''"></span>
        @endforelse
    </div>
</div>
