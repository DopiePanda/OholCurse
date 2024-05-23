<?php

namespace App\Livewire\News\Ads;

use Livewire\Component;
use Livewire\Attributes\On; 

use App\Models\NewsAd;

class Download extends Component
{
    public $ads;
    public $progress;

    public function mount($id)
    {
        $ad = NewsAd::find($id);
        $ad->clicks = $ad->clicks + 1;
        $ad->save();
        
        $this->ads = NewsAd::limit(10)->get()->random(2);
        $this->progress = 1;
    }

    public function render()
    {
        return view('livewire.news.ads.download');
    }

    #[On('increase-bar')] 
    public function updaterogress()
    {
        if($this->progress < 100)
        {
            $this->progress = $this->progress + 1;
        }
    }
}
