<?php

namespace App\Livewire\News;

use Livewire\Component;

use App\Models\NewsArticle;
use App\Models\NewsAd;

class Index extends Component
{
    public $articles;
    public $ads;

    public function mount()
    {
        $this->articles = NewsArticle::with('images')->where('enabled', 1)->orderBy('id', 'desc')->get();
        $this->ads = NewsAd::where('enabled', 1)->orderBy('id', 'desc')->get();
    }

    public function render()
    {
        return view('livewire.news.index');
    }
}
