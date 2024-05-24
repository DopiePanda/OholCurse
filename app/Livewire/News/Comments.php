<?php

namespace App\Livewire\News;

use Livewire\Component;
use Auth;

use App\Models\NewsArticleComment;

class Comments extends Component
{
    public $article;
    public $comments;

    public $user_comment;
    public $replies_to = null;

    public $edit_comment_id;
    public $edit_comment_content;


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
        $validated = $this->validate([
            'user_comment' => 'required|string|min:1|max:250',
            'replies_to' => 'nullable|numeric'
        ]);

        NewsArticleComment::create(
            [
                'article_id' => $this->article,
                'user_id' => Auth::user()->id,
                'replies_to' => $this->replies_to,
                'comment' => $this->user_comment,
            ]
        );

        $this->clearForm();
        $this->getComments();
    }

    public function setReply($id)
    {
        $this->replies_to = $id;
    }

    public function deleteComment($id)
    {
        $comment = NewsArticleComment::find($id);

        if($comment->user_id == Auth::id())
        {
            $comment->comment = 'Comment deleted by user';
            $comment->save();
        }

        $this->getComments();
    }

    public function editComment($id)
    {
        $comment = NewsArticleComment::find($id);

        if($comment->user_id == Auth::id())
        {
            $this->edit_comment_id = $comment->id;
            $this->edit_comment_content = $comment->comment;
        }
    }

    public function saveComment()
    {
        $comment = NewsArticleComment::find($this->edit_comment_id);
        $comment->comment = $this->edit_comment_content;
        $comment->save();

        $this->edit_comment_id = null;
        $this->edit_comment_content = null;

        $this->getComments();
    }

    public function clearForm()
    {
        $this->user_comment = null;
        $this->replying_to = null;
    }

    public function render()
    {
        return view('livewire.news.comments');
    }
}
