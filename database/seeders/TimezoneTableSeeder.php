<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

use App\Models\Timezone;

class TimezoneTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $timestamp = time();

        foreach (timezone_identifiers_list() as $zone) 
        {
            date_default_timezone_set($zone);
            $zones['offset'] = date('P', $timestamp);
            $zones['diff_from_gtm'] = 'UTC/GMT '.date('P', $timestamp);
  
            Timezone::updateOrCreate(['name' => $zone], $zones);
        }
    }
}
