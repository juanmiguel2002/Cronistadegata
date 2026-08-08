<?php

namespace App\Livewire\Admin;

use App\Models\Setting;
use Illuminate\Support\Facades\Cache;
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
    public $resized_width;
    public $resized_height;

    public function selectTab($tab){
        $this->tab = $tab;
    }
    public function mount()
    {
        $this->tab = Request('tab') ? Request('tab') : $this->defaultTab;
        // Obtener el valor actual de la paginación desde la base de datos
        $this->paginationLimit = Setting::where('key', 'pagination_limit')->value('value');
        $this->pagination_destacados = Setting::where('key', 'pagination_destacados')->value('value');
        // Mida de les miniatures "resized" (per defecte 512x320)
        $this->resized_width = Setting::where('key', 'resized_width')->value('value') ?? 512;
        $this->resized_height = Setting::where('key', 'resized_height')->value('value') ?? 320;

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
        Cache::forget('setting_pagination_limit');

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
        Cache::forget('setting_pagination_destacados');

        // Emitir un mensaje de éxito al administrador
        session()->flash('success', 'El número de elementos por página se actualizó correctamente.');
    }

    public function updateResizedSize()
    {
        $this->validate([
            'resized_width'  => 'required|integer|min:50|max:2000',
            'resized_height' => 'required|integer|min:50|max:2000',
        ], [
            'resized_width.required'  => "L'amplada és obligatòria.",
            'resized_height.required' => "L'alçada és obligatòria.",
            'resized_width.min'  => "L'amplada mínima és 50 px.",
            'resized_height.min' => "L'alçada mínima és 50 px.",
            'resized_width.max'  => "L'amplada màxima és 2000 px.",
            'resized_height.max' => "L'alçada màxima és 2000 px.",
        ]);

        Setting::updateOrCreate(['key' => 'resized_width'], ['value' => $this->resized_width]);
        Setting::updateOrCreate(['key' => 'resized_height'], ['value' => $this->resized_height]);
        Cache::forget('setting_resized_width');
        Cache::forget('setting_resized_height');

        session()->flash('success', "La mida de les miniatures s'ha actualitzat. Les imatges noves faran servir la nova mida ({$this->resized_width}×{$this->resized_height} px).");
    }

    public function render()
    {
        return view('livewire.admin.personalizar');
    }
}
