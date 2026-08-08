<?php

namespace App\Livewire\Admin;

use App\Models\Carousel;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class Carrusel extends Component
{
    use WithFileUploads;

    public $images = [];
    public $titles = [];
    public $newImages = [];
    public $order = [];

    public $isUpdateImage = false;
    public $image_id;
    public $title;
    public $newImage;

    protected $listeners = [
        'confirmDeleteImage',
    ];

    public function mount()
    {
        // Cargar las imágenes actuales desde la base de datos
        $this->images = Carousel::orderBy('orden')->get();
    }

    public function addImage() {
        $this->reset(['newImages', 'titles', 'isUpdateImage', 'image_id', 'title', 'newImage']);
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

    public function editImage($id) {
        $image = Carousel::find($id);
        $this->image_id = $image->id;
        $this->title = $image->title;
        $this->isUpdateImage = true;
        $this->showModal();
    }

    public function updateImage()
    {
        $this->validate([
            'title' => 'required|string|max:255',
            'newImage' => 'nullable|image|max:2048',
        ]);

        $carouselItem = Carousel::find($this->image_id);

        if ($this->newImage) {
            Storage::disk('public')->delete($carouselItem->image_path);
            $carouselItem->image_path = $this->newImage->store('carousel', 'public');
        }

        $carouselItem->title = $this->title;
        $carouselItem->save();

        $this->images = Carousel::orderBy('orden')->get();
        $this->reset(['isUpdateImage', 'image_id', 'title', 'newImage']);
        $this->dispatch('showToastr', ['type' => 'success', 'message' => 'Imagen actualizada correctamente.']);
        $this->dispatch('showModal', false);
    }

    public function deleteImage($id) {
        $this->dispatch('deleteImage', ['id' => $id]);
    }

    public function confirmDeleteImage($id)
    {
        $carouselItem = Carousel::find($id);

        if ($carouselItem) {
            Storage::disk('public')->delete($carouselItem->image_path);
            $carouselItem->delete();
        }

        $this->images = Carousel::orderBy('orden')->get();
        $this->dispatch('showToastr', ['type' => 'success', 'message' => 'Imagen eliminada correctamente.']);
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
