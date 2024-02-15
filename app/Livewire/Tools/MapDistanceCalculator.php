<?php

namespace App\Livewire\Tools;

use Illuminate\Support\Facades\Session;
use Livewire\Component;

use Carbon\CarbonInterval;

class MapDistanceCalculator extends Component
{
    public $from;
    public $to;
    public $label;

    public $x_distance;
    public $y_distance;

    public $calculations;

    public $speeds;
    public $estimated_times;

    public function mount()
    {
        if(auth()->user()->can('use map distance calculator') != true)
        {
            return redirect(route('search'));
        }

        $this->calculations = Session::get('calculations', []);
        $this->speeds = $this->setMovementSpeeds();
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

        $this->estimated_times = $this->getEstimatedTime();

        $identifier = strval($this->x_distance.$this->y_distance);

        $payload = [
            'x' => $this->x_distance,
            'y' => $this->y_distance,
            'label' => $validated["label"],
            'url_from' => $validated["from"],
            'url_to' => $validated["to"],
        ];

        Session::put("calculations.$identifier", $payload);

        /*
        Session::put("calculations.$identifier.x", $this->x_distance);
        Session::put("calculations.$identifier.y", $this->y_distance);
        Session::put("calculations.$identifier.label", $validated["label"]);
        */
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
        $this->label = null;
        $this->x_distance = null;
        $this->y_distance = null;
    }

    public function edit($id)
    {
        if(Session::exists("calculations.$id"))
        {
            $values = Session::get("calculations.$id");

            $this->to = $values["url_to"];
            $this->from = $values["url_from"];
            $this->label = $values["label"];

            $this->x_distance = null;
            $this->y_distance = null;
        }
    }

    public function remove($id)
    {
        Session::forget("calculations.$id");

        if(count(Session::get('calculations')) == 0)
        {
            Session::forget('calculations');
        }

        $this->calculations = Session::get('calculations', []);
    }

    public function flush()
    {
        Session::forget('calculations');
        $this->calculations = Session::get('calculations', []);
    }

    public function setMovementSpeeds()
    {
        $speeds = [
            'ground' => [
                'walking' => 3.75,
                'horse' => 7.5,
                'truck' => 15,
                'car' => 22.5,
            ],
            'road' => [
                'walking' => 5.626,
                'horse' => 11.25,
                'truck' => 22.5,
                'car' => 33.75,
            ],
        ];

        return $speeds;
    }

    public function getEstimatedTime()
    {
        $estimated_times = [];

        foreach ($this->speeds as $tile_key => $tile_value) 
        {
            foreach ($tile_value as $method_key => $method_value) 
            {
                $x = abs($this->x_distance);
                $y = abs($this->y_distance);
                $distance = sqrt($x*$x + $y*$y);

                $estimated_times[$tile_key][$method_key] = CarbonInterval::seconds($distance / $method_value)->cascade()->forHumans(['short' => true]);
            }
        }

        return $estimated_times;
    }
}
