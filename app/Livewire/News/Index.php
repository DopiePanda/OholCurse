<?php

namespace App\Livewire\News;

use Livewire\Component;

use App\Models\NewsArticle;
use App\Models\NewsAd;

class Index extends Component
{
    public $articles;
    public $ads;

    public $ads_displayed;

    public function mount()
    {
        $this->articles = NewsArticle::with('images')->where('enabled', 1)->orderBy('id', 'desc')->get();
        $this->ads = NewsAd::where('enabled', 1)->orderBy('id', 'desc')->get();

        $this->ads_displayed = [];
    }

    public function render()
    {
        return view('livewire.news.index');
    }

    public function addToDisplayedAds($id)
    {
        array_push($this->ads_displayed, $id);
    }
}
