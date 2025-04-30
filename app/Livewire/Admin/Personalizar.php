<?php

namespace App\Livewire\Admin;

use App\Models\Setting;
use Illuminate\Support\Facades\Request;
use Livewire\Component;

class Personalizar extends Component
{
    public $tab;
    public $defaultTab = 'personalizar';
    protected $queryString = ['tab' => ['keep' => true]];
    public $paginationLimit;
    public $pagination_limit;
    public $pagination_destacados;

    public function selectTab($tab){
        $this->tab = $tab;
    }
    public function mount()
    {
        $this->tab = Request('tab') ? Request('tab') : $this->defaultTab;
        // Obtener el valor actual de la paginación desde la base de datos
        $this->paginationLimit = Setting::where('key', 'pagination_limit')->value('value');
        $this->pagination_destacados = Setting::where('key', 'pagination_destacados')->value('value');

    }

    public function updatePaginationLimit()
    {
        $this->validate([
            'paginationLimit' => 'required|integer|min:1|max:100',
        ]);

        // Actualizar el valor en la base de datos
        Setting::updateOrCreate(
            ['key' => 'pagination_limit'],
            ['value' => $this->paginationLimit]
        );

        // Emitir un mensaje de éxito al administrador
        session()->flash('success', 'El número de elementos por página se actualizó correctamente.');
    }

    public function updatePaginationMesVisitas() {
        $this->validate([
            'pagination_destacados' => 'required|integer|min:1|max:100',
        ]);

        // Actualizar el valor en la base de datos
        Setting::updateOrCreate(
            ['key' => 'pagination_destacados'],
            ['value' => $this->pagination_destacados]
        );

        // Emitir un mensaje de éxito al administrador
        session()->flash('success', 'El número de elementos por página se actualizó correctamente.');
    }

    public function render()
    {
        return view('livewire.admin.personalizar');
    }
}
