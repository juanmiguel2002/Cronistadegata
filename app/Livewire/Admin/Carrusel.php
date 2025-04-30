<?php

namespace App\Livewire\Admin;

use App\Models\Carousel;
use Livewire\Component;
use Livewire\WithFileUploads;

class Carrusel extends Component
{
    use WithFileUploads;

    public $images = [];
    public $titles = [];
    public $newImages = [];
    public $order = [];

    public function mount()
    {
        // Cargar las imágenes actuales desde la base de datos
        $this->images = Carousel::orderBy('orden')->get();
    }

    public function addImage() {
        $this->showModal();
    }

    public function showModal() {
        $this->resetErrorBag();
        $this->dispatch('showModal');
    }

    // Subir una nueva imagen al carrusel
    public function addImages()
    {
        $this->validate([
            'newImages.*' => 'required|image|max:2048',
            'titles.*' => 'required|string|max:255',
        ]);

        foreach ($this->newImages as $index => $image) {
            $path = $image->storeAs('carousel', $this->titles[$index], 'public');

            Carousel::create([
                'title' => $this->titles[$index] ?? 'Sin título',
                'image_path' => $path,
            ]);
        }
        $this->reset(['newImages', 'titles']);
        $this->dispatch('showToastr', ['type' => 'success', 'message' => 'Imágenes subidas exitosamente.']);
        $this->dispatch('showModal', false);

    }

    public function updateOrder($order)
    {
        foreach ($order as $index => $id) {
            $carouselItem = Carousel::find($id);
            $carouselItem->update(['orden' => $index + 1]);
        }
        $this->images = Carousel::orderBy('orden')->get();
    }


    public function render()
    {
        return view('livewire.admin.carrusel');
    }
}
