<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use LaravelDaily\LaravelCharts\Classes\LaravelChart;

class StatisticController extends Controller
{
    public function index()
    {
        $chart_options = [
            'chart_title' => 'Lives this week',
            'report_type' => 'group_by_date',
            'model' => 'App\Models\LifeLog',
            'conditions' => [
                ['name' => 'Lives', 'condition' => 'age > 3', 'color' => 'black', 'fill' => true],
            ],
            'group_by_field' => 'created_at',
            'group_by_period' => 'day',
            'chart_type' => 'line',
            'filter_field' => 'created_at',
            'filter_days' => 7,
            'filter_period' => 'week',
        ];

        $chart1 = new LaravelChart($chart_options);
        
        return view('stats.index', compact('chart1'));
    }
}
