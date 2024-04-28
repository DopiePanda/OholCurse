<?php

namespace App\Livewire\News;

use Livewire\Component;

use App\Models\NewsArticleComment;

class Comments extends Component
{
    public $comments;
    public $user_comment;

    public function mount($article)
    {
        $this->comments = NewsArticleComment::with('user', 'replies')
            ->where('article_id', $article)
            ->where('replies_to', null)
            ->get();
       //dd($this->comments);
    }

    public function render()
    {
        return view('livewire.news.comments');
    }
}
