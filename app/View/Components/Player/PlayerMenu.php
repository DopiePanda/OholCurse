<?php

namespace App\View\Components\Player;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

use App\Models\Yumlog;

class PlayerMenu extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        public string $hash;
        public string $reportCount;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.player.menu', ['hash' => $this->hash]);
    }
}
