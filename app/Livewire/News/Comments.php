<?php

namespace App\Livewire\News;

use Livewire\Component;

use App\Models\NewsArticleComment;

class Comments extends Component
{
    public $article;
    public $comments;

    public $user_comment;
    public $replying_to;


    public function mount($article)
    {
        $this->article = $article;
        $this->getComments();
    }

    public function getComments()
    {
        $this->comments = NewsArticleComment::with('user', 'replies')
            ->where('article_id', $this->article)
            ->where('replies_to', null)
            ->get();
    }

    public function postComment()
    {

    }

    public function render()
    {
        return view('livewire.news.comments');
    }
}
