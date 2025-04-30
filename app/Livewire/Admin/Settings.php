<?php

namespace App\Livewire\Admin;

use App\Models\GeneralSettings;
use Livewire\Component;
use Livewire\WithFileUploads;

class Settings extends Component
{
    use WithFileUploads;

    public $tab;
    public $defaultTab = 'general_settings';
    protected $queryString = ['tab' => ['keep' => true]];
    public $settings;
    public $site_logo, $current_logo;
    public $current_favicon, $site_favicon;
    public $site_name, $site_email, $site_meta_description, $site_meta_keywords;

    public function selectTab($tab){
        $this->tab = $tab;
    }
    public function mount(){
        $this->tab = Request('tab') ? Request('tab') : $this->defaultTab;

        $this->settings = GeneralSettings::take(1)->first();

        if (!is_null( $this->settings)) {
            $this->site_name = $this->settings->site_name;
            $this->site_email = $this->settings->site_email;
            $this->site_meta_description =  $this->settings->site_description;
            $this->site_meta_keywords =  $this->settings->site_keywords;
        }

        $this->current_logo = settings()->site_logo ? asset('storage/' . settings()->site_logo) : null;
        $this->current_favicon = settings()->site_favicon ? asset('storage/' . settings()->site_favicon) : null;

    }
    public function updateSiteInfo(){
        $this->validate([
           'site_name' =>'required|string',
           'site_email' => 'nullable|email',
        ]);

        GeneralSettings::updateOrCreate(['id' => 1], [
           'site_name' => $this->site_name,
           'site_email' => $this->site_email,
           'site_description' => $this->site_meta_description,
           'site_keywords' => $this->site_meta_keywords,
        ]);

        $this->dispatch('showToastr', ['type' => 'success', 'message' => 'General Settings updated successfully']);
    }
    public function uploadSiteLogo()
    {
        // Almacenar la imagen en el directorio 'public/site-logos'
        $filePath = $this->site_logo->storeAs('/images/site', 'Logo_'. uniqid() .'.'. $this->site_logo->getClientOriginalExtension(),'public');

        // Actualizar la base de datos con la ruta de la imagen
        $this->settings->update(['site_logo' => $filePath]);

        // Opcional: Emitir un evento o redirigir al usuario con un mensaje de éxito
        $this->dispatch('showToastr', ['type' => 'success', 'message' => 'Site logo updated successfully.']);
        $this->current_logo = asset('storage/' . $filePath);
    }

    public function uploadFavicon(){

        // Generar un nombre único para el archivo
        $fileName = 'favicon_' . uniqid() . '.' . $this->site_favicon->getClientOriginalExtension();

        // Guardar el archivo en la ubicación especificada
        $filePath = $this->site_favicon->storeAs('images/site', $fileName, 'public');

        // Actualizar la base de datos con la nueva ruta
        $this->settings->update(['site_favicon' => $filePath]);

        // Emitir un evento para notificar al usuario
        $this->dispatch('showToastr', ['type' => 'success','message' => 'Site Favicon updated successfully.']);

        // Actualizar la propiedad para reflejar el cambio en la vista
        $this->current_favicon = asset('storage/' . $filePath);
    }

    

    public function render()
    {
        return view('livewire.admin.settings');
    }
}
