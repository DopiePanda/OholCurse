<?php

namespace App\Livewire\News;

use Livewire\Component;

use App\Models\NewsArticle;

class Marquee extends Component
{
    public $articles;

    public function mount()
    {
        $this->articles = NewsArticle::where('enabled', 1)->limit(5)->orderBy('id', 'desc')->get();
    }

    public function render()
    {
        return view('livewire.news.marquee');
    }
}
