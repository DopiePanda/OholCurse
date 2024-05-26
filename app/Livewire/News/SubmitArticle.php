<?php

namespace App\Livewire\News;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;

use Illuminate\Support\Str;
use Auth;

use App\Models\NewsArticle;
use App\Models\NewsArticleImage;
use App\Models\NewsAgency;

class SubmitArticle extends Component
{
    use WithFileUploads;

    #[Validate('required|image|max:1024|dimensions:min_width=350,min_height=350')] // 1MB Max
    public $image;

    #[Validate('required|string|in:report,life,guide,music')]
    public $type;

    #[Validate('required|string|min:10|max:70')]
    public $title;

    #[Validate('required|string|min:100|max:5000')]
    public $content;

    #[Validate('nullable')]
    public $author;

    #[Validate('sometimes|exists:news_agencies,name|nullable')]
    public $agency;

    public $agencies;

    public $submitted = false;

    public function mount()
    {
        $this->agencies = NewsAgency::all();
        $this->type = 'report';
    }

    public function render()
    {
        return view('livewire.news.submit-article');
    }

    public function submit()
    {
        $validated = $this->validate();
        
        $slug = Str::of($this->title)->slug('-');

        $article = NewsArticle::create([
            'user_id' => Auth::id(),
            'type' => $this->type,
            'enabled' => 0,
            'slug' => $slug,
            'title' => $this->title,
            'content' => $this->content,
            'author' => $this->author,
            'agency' => $this->agency,
        ]);

        // Store the file in the "assets/news-articles" directory, on the "public" disk
        $image = $this->image->storePublicly('assets/news-articles', 'public');

        $article_image = NewsArticleImage::create([
            'article_id' => $article->id,
            'user_id' => Auth::id(),
            'position' => 'primary',
            'image_url' => $image,
        ]);

        $this->submitted = true;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->image = null;
        $this->title = null;
        $this->content = null;
        $this->author = null;
        $this->agency = null;
    }
}
