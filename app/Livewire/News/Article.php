<?php

namespace App\Livewire\News;

use Livewire\Component;

use App\Models\NewsArticle;

class Article extends Component
{
    public $article;
    public $images = [];

    public function mount($id, $slug)
    {
        $this->getArticle($id);
    }

    public function render()
    {
        return view('livewire.news.article');
    }

    public function getArticle($id)
    {
        $this->article = NewsArticle::with('images', 'authors')->where('id', $id)->first();
        
        foreach($this->article->images as $image)
        {
            $this->images[$image->position] = [
                'id' => $image->id,
                'article_id' => $image->article_id,
                'caption' => $image->caption,
                'image_url' => $image->image_url,
                'created' => $image->created_at,
                'updated' => $image->updated_at,
            ];
        }

        $this->article->views = $this->article->views + 1;
        $this->article->save();
    }
}
