<?php

namespace App\Livewire\News;

use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\Attributes\Validate;

use Illuminate\Support\Str;
use Auth;

use App\Models\NewsArticle;
use App\Models\NewsArticleImage;

class SubmitArticle extends Component
{
    use WithFileUploads;

    #[Validate('image|max:1024')] // 1MB Max
    public $image;

    #[Validate('required|string|min:10|max:100')]
    public $title;

    #[Validate('required|string|min:100|max:5000')]
    public $content;

    #[Validate('nullable')]
    public $author;

    #[Validate('nullable|in:news_agencies,name')] // 1MB Max
    public $agency;

    public $agencies;

    public $submitted = false;

    public function mount()
    {

    }

    public function render()
    {
        return view('livewire.news.submit-article');
    }

    public function submit()
    {
        $validated = $this->validate();
        // Store the file in the "photos" directory, with "public" visibility in a configured "s3" disk
        $image = $this->image->store(path: 'assets/news-articles');
        $slug = Str::of($this->title)->slug('-');

        $article = NewsArticle::create([
            'type' => 'report',
            'enabled' => 0,
            'title' => $this->title,
            'content' => $this->content,
            'author' => $this->author,
            'agency' => $this->agency,
        ]);

        $article_image = NewsArticleImage::create([
            'article_id' => $article->id,
            'user_id' => Auth::id(),
            'position' => 'primary',
            'image_url' => $image,
        ]);

        $this->submitted = true;
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
