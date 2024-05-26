<?php

namespace App\Livewire\News;

use Livewire\Component;

use App\Models\NewsArticle;
use App\Models\NewsAd;
use App\Models\NewsAgency;

class Index extends Component
{
    public $articles;
    public $agencies;
    public $ads;

    public $article_categories;
    public $active_filter;

    public function mount()
    {
        $this->articles = NewsArticle::with('images')->where('enabled', 1)->orderBy('id', 'desc')->get();
        $this->article_categories = $this->articles->pluck('type')->unique();

        $this->ads = NewsAd::inRandomOrder()->limit(3)->get();
        $this->agencies = NewsAgency::withCount('articles')->get();
    }

    public function render()
    {
        return view('livewire.news.index');
    }

    public function filterByType($type)
    {
        $this->articles = NewsArticle::with('images')
            ->where('enabled', 1)
            ->where('type', $type)
            ->orderBy('id', 'desc')
            ->get();

        $this->active_filter = 'Type: '.$type;
        $this->agencies = NewsAgency::withCount('articles')->get();
    }

    public function filterByAgency($agency)
    {
        $this->articles = NewsArticle::with('images')
            ->where('enabled', 1)
            ->where('agency', $agency)
            ->orderBy('id', 'desc')
            ->get();

        $this->active_filter = 'Agency: '.$agency;
        $this->agencies = NewsAgency::withCount('articles')->get();
    }

    public function resetFilters()
    {
        $this->articles = NewsArticle::with('images')->where('enabled', 1)->orderBy('id', 'desc')->get();
        $this->active_filter = null;
        $this->agencies = NewsAgency::withCount('articles')->get();
    }
}
