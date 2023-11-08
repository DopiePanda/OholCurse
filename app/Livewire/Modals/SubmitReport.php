<?php

namespace App\Livewire\Modals;

use LivewireUI\Modal\ModalComponent;
use Carbon\Carbon;
use Auth;

use App\Models\User;
use App\Models\Report;
use App\Models\Timezone;

class SubmitReport extends ModalComponent
{

    public $current_step, $last_step;

    public $user_timezone;
    public $time_start, $time_end;
    public $unix_start, $unix_end;
    
    public $timezones;
    
    public $character_name, $curse_name, $description;
    public $phex_name, $phex_hash;

    public function mount()
    {
        $this->current_step = 1;
        $this->last_step = 4;

        $this->timezones = Timezone::Orderby('name')->get();
        $this->user_timezone = Auth::user()->timezone ?? 'UTC';

        $this->setDefaultTimeDate();
    }

    public function render()
    {
        return view('livewire.modals.submit-report');
    }

    public function validateReport()
    {
        $this->validate([
            'character_name' => ['required', 'string'],
            'curse_name' => ['nullable', 'string'],
            'description' => ['required', 'string', 'max:500'],
            'phex_name' => ['nullable', 'string'],
            'phex_hash' => ['nullable', 'string', 'size:8'],
        ]);

        $this->current_step = 4;
    }

    public function submitReport()
    {
        try {
            Report::updateOrCreate([
                'user_id' => Auth::user()->id,
                'unix_from' => $this->unix_start,
                'unix_to' => $this->unix_end,
                'character_name' => $this->character_name,
            ],[
                'confirmed' => 0,
                'curse_name' => $this->curse_name,
                'description' => $this->description,
                'phex_name' => $this->phex_name,
                'phex_hash' => $this->phex_hash,
            ]);

            $this->emitTo('dashboard', 'reportAdded');
            $this->alert('success', 'Report was successfully submitted!');
            $this->closeModal();

        } catch (\Throwable $th) {
            //throw $th;
        }
        
    }


    public function autoFillDateTime()
    {
        $this->setDefaultTimeDate();
        $this->confirmDateTime();
    }

    public function manuallyFillDateTime()
    {
        $this->current_step = 2;
    }

    public function confirmDateTime()
    {
        $this->validate([
            'time_start' => ['required', 'before_or_equal:time_end', 'date_format:Y-m-d H:i'],
            'time_end' => ['required','before_or_equal:'.now(Auth::user()->timezone), 'date_format:Y-m-d H:i'],
            'user_timezone' => ['required', 'exists:timezones,name'],
        ]);

        $this->getTimestampFromDateTime();

        $this->current_step = 3;
    }

    private function setDefaultTimeDate()
    {
        $carbon = Carbon::now();
        $this->user_timezone = Auth::user()->timezone ?? 'UTC';
        $tz = $this->user_timezone;

        $this->time_end = $carbon->copy()->tz($tz)->format('Y-m-d H:i');
        $this->time_start = $carbon->copy()->tz($tz)->subHour()->format('Y-m-d H:i');
    }

    private function getTimestampFromDateTime()
    {
        /* $this->unix_start = Carbon::parse($this->selected_date.' '.$this->time_start)->timestamp;
        $this->unix_end = Carbon::parse($this->selected_date.' '.$this->time_end)->timestamp; */

        $start = $this->time_start;
        $end = $this->time_end;
        $tz = $this->user_timezone;

        $format = "H:i";

        $this->unix_start = Carbon::createFromFormat('Y-m-d H:i' , $start, $tz)->timestamp;
        $this->unix_end = Carbon::createFromFormat('Y-m-d H:i' , $end, $tz)->timestamp;
    }

    public function previousStep()
    {
        if($this->current_step > 1 && $this->current_step <= $this->last_step)
        {
            $this->current_step = $this->current_step - 1;
        }
        
    }

    public function nextStep()
    {
        if($this->current_step >= 1 && $this->current_step < $this->last_step)
        {
            $this->current_step = $this->current_step + 1;
        }
        
    }
}
