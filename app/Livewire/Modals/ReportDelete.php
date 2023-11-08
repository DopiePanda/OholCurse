<?php

namespace App\Livewire\Modals;

use LivewireUI\Modal\ModalComponent;
use Auth;

use App\Models\Report;

class ReportDelete extends ModalComponent
{
    public $report;

    public function mount(Report $report)
    {
        if(Auth::user()->id == $report->user_id)
        {
            $this->report = $report;
        }else{
            $this->report = null;
        }
        
    }

    public function render()
    {
        return view('livewire.modals.report-delete');
    }

    public function deleteReport()
    {
        try {
            $this->report->delete();
            $this->emitTo('dashboard', 'reportDeleted');
            $this->alert('success', 'Report was successfully deleted!');
            $this->closeModal();
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
}
