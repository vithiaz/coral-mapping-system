<?php

namespace App\Livewire\Admin;

use App\Models\Coral;
use Livewire\Component;
use Livewire\Attributes\Rule;

class AddCoralPoints extends Component
{

    // Model Variable
    public $storedCorals;

    // Binding Variable
    public $condition;
    public $radius;
    public $direct_save;
    
    public $cord_data = [];
    #[Rule('required')]
    public $long;
    #[Rule('required')]
    public $lat;

    public function updatedDirectSave(){
        $this->long = null;
        $this->lat = null;
    }

    public function updatedRadius() {
        if ($this->long && $this->lat && !$this->direct_save) {
            $data = [
                'long' => $this->long,
                'lat' => $this->lat,
                'radius' => (int) $this->radius,
                'condition' => $this->condition,
            ];
    
            if ($this->direct_save) {
                array_push($this->cord_data, $data);
            }
            $this->dispatch('add-map-marker', cordData: $data, storedCordData: $this->reset_array_index($this->cord_data));
        }
    }

    public function tempFunc() {
        return '123';
    }

    public function mount() {
        $this->condition = 'baik';
        $this->radius = 10;
        $this->direct_save = false;

        $this->long = null;
        $this->lat = null;

        $this->storedCorals = Coral::get()->toArray();
    }

    public function render()
    {
        return view('livewire.admin.add-coral-points');
    }

    public function set_cord_data($long, $lat) {
        $this->long = $long;
        $this->lat = $lat;
        $this->validate();
        
        $data = [
            'long' => $long,
            'lat' => $lat,
            'radius' => (int) $this->radius,
            'condition' => $this->condition,
        ];

        if ($this->direct_save) {
            array_push($this->cord_data, $data);
        }
        $this->dispatch('add-map-marker', cordData: $data, storedCordData: $this->reset_array_index($this->cord_data));
    }

    public function add_coord() {
        if (!$this->direct_save) {            
            $this->validate();

            $data = [
                'long' => $this->long,
                'lat' => $this->lat,
                'radius' => (int) $this->radius,
                'condition' => $this->condition,
            ];
            array_push($this->cord_data, $data);

            $this->long = null;
            $this->lat = null;
            $this->dispatch('add-map-marker', cordData: [], storedCordData: $this->reset_array_index($this->cord_data));
        }
    }

    public function store_coordinates() {
        foreach ($this->cord_data as $cord) {
            $Coral = new Coral;
            $Coral->condition = $cord['condition'];
            $Coral->radius = $cord['radius'];
            $Coral->long = $cord['long'];
            $Coral->lat = $cord['lat'];
            $Coral->save();
        }

        $this->condition = 'baik';
        $this->radius = 10;
        $this->direct_save = false;

        $this->long = null;
        $this->lat = null;
        
        $this->dispatch('display-message', message: ['success', 'koordinat tersimpan']);
        $this->dispatch('add-map-marker', cordData: [], storedCordData: $this->reset_array_index($this->cord_data));
    }

    public function empty_coordinates() {
        $this->long = null;
        $this->lat = null;
        $this->cord_data = [];

        $this->dispatch('add-map-marker', cordData: [], storedCordData: []);
    }
    
    public function delete_coord($cord_index) {
        if ($this->cord_data[$cord_index]) {
            unset($this->cord_data[$cord_index]);
            $this->dispatch('add-map-marker', cordData: [], storedCordData: $this->reset_array_index($this->cord_data));
        }
    }

    private function reset_array_index($array) {
        $sortedIndex = [];
        foreach ($array as $cord) {
            array_push($sortedIndex, $cord);
        }
        return $sortedIndex;
    }

}
