<?php

namespace App\Livewire\Tools;

use Illuminate\Support\Facades\Session;
use Livewire\Component;

class MapDistanceCalculator extends Component
{
    public $from;
    public $to;
    public $label;

    public $x_distance;
    public $y_distance;

    public $calculations;

    public function mount()
    {
        if(auth()->user()->can('use map distance calculator') != true)
        {
            return redirect(route('search'));
        }

        $this->calculations = Session::get('calculations');
    }

    public function render()
    {   
        return view('livewire.tools.map-distance-calculator');
    }

    public function calculate()
    {
        $validated = $this->validate([ 
            'from' => 'required|url|starts_with:https://onemap.wondible.com/|different:to',
            'to' => 'required|url|starts_with:https://onemap.wondible.com/|different:from',
            'label' => 'nullable|string'
        ]);

        $from = $this->getUrlParameters($validated['from']);
        $to = $this->getUrlParameters($validated['to']);

        $x_difference = $from[0] - $to[0];
        $this->x_distance = -$x_difference;
        
        $y_difference = $from[1] - $to[1];
        $this->y_distance = -$y_difference;

        $identifier = strval($this->x_distance.$this->y_distance);

        Session::push("calculations.$identifier.x", $this->x_distance);
        Session::push("calculations.$identifier.y", $this->y_distance);
        Session::push("calculations.$identifier.label", $this->label);

        $this->calculations = Session::get('calculations');
    }

    public function getUrlParameters($input)
    {
        $query  = explode('&', $input);
        $array = array();

        foreach($query as $param)
        {
            list($name, $value) = explode('=', $param);
            $array[] = urldecode($value);
        }

        return $array;
    }

    public function invert()
    {
        $to = $this->to;
        $from = $this->from;

        $this->to = $from;
        $this->from = $to;

        $this->calculate();

        //dd(Session::all());
        
    }

    public function clear()
    {
        $this->to = null;
        $this->from = null;
        $this->x_distance = null;
        $this->y_distance = null;
    }

    public function flush()
    {
        Session::forget('calculations');
        $this->calculations = Session::get('calculations');
    }

    public function remove($id)
    {
        Session::forget("calculations.$id");
        $this->calculations = Session::get('calculations');
    }
}
