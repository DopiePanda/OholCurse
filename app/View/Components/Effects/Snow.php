<?php

namespace App\View\Components\Effects;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Snow extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        if(env('EFFECTS_ENABLED') == 'true')
        {
            return view('components.effects.snow');
        }else
        {
            return view('components.effects.none');
        }
    }
}
