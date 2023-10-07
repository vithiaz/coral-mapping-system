<?php

namespace App\Livewire;

use App\Models\Coral;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class CoralPointsList extends Component
{
    // Model Variable
    public $storedCoralsQuery;
    public $storedCorals;

    // Binding Variable
    public $selectedPointId;
    public $filterGood = false;
    public $filterBad = false;


    public function mount() {
        $this->selectedPointId = null;
    }
    
    public function render()
    {
        $this->getCoralsData();
        return view('livewire.coral-points-list');
    }

    private function get_coral($coralId) {
        foreach ($this->storedCorals as $coral) {
            if ($coral['id'] == $coralId) {
                return $coral;
            }
        }
        return false;

    }

    public function hover_location($coralId) {
        if ($this->selectedPointId != $coralId) {
            $coral_hover = $this->get_coral($coralId);
            if ($coral_hover) {
                $data = [
                    'long' => $coral_hover['long'],
                    'lat' => $coral_hover['lat'],
                    'radius' => $coral_hover['radius'],
                    'condition' => $coral_hover['condition'],
                ];
                $this->dispatch('add-map-marker', cordData: $data, storedCordData: $this->storedCorals);
                $this->selectedPointId = $coralId;
            }
        } else {
            $this->dispatch('add-map-marker', cordData: [], storedCordData: $this->storedCorals);
            $this->selectedPointId = null;
        }
    }

    public function delete_cord($coralId) {
        if (Auth::check()) {
            $coral_delete = Coral::find($coralId);
            if ($coral_delete) {
                $coral_delete->delete();
            }
    
            $this->storedCorals = Coral::get()->toArray();
            $this->selectedPointId = null;
            $this->dispatch('add-map-marker', cordData: [], storedCordData: $this->storedCorals);
            $this->dispatch('reset-heatmap');
        }
    }

    private function getCoralsData() {
        $corals = [];
        if ($this->filterGood || $this->filterBad) {
                        
            $corals_good = [];
            $corals_bad = [];
            if ($this->filterGood) {
                $corals_good = Coral::where('condition', '=', 'baik')->get()->toArray();
            }
            if ($this->filterBad) {
                $corals_bad = Coral::where('condition', '=', 'rusak')->get()->toArray();
            }
            
            if ($this->filterGood == $this->filterBad) {
                $corals = Coral::get()->toArray();
            } else {
                $corals = array_merge($corals_good, $corals_bad);
            }
        } else {
            $corals = Coral::get()->toArray();
        }
        
        $this->storedCorals = $corals;
    }

    public function toggle_filter($filter) {
        if ($filter == 'good') {
            $this->filterGood = !$this->filterGood;
        }
        if ($filter == 'bad') {
            $this->filterBad = !$this->filterBad;
        }
        $this->getCoralsData();
        $this->dispatch('add-map-marker', cordData: [], storedCordData: $this->storedCorals);
        $this->dispatch('reset-heatmap');
    }

}
