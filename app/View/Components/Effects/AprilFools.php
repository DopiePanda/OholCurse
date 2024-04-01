<?php

namespace App\View\Components\Effects;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class AprilFools extends Component
{
    public $triggered;

    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //session(['times-triggered' => 0]);
        $this->triggered = session('times-triggered', 0);
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.effects.april-fools');
    }
}
