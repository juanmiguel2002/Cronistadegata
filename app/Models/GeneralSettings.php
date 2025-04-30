<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GeneralSettings extends Model
{
    //
    protected $table = 'general_settings';
    protected $fillable = [
        'site_name',
        'site_email',
        'site_keywords',
        'site_description',
        'site_logo',
        'site_favicon'
    ];
}
