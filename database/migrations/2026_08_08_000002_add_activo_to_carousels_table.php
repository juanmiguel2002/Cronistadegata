<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * Assegura la columna `activo` a `carousels`. En producció ja existeix
 * (schema drift), així que aquesta migració és un no-op allà; protegeix
 * instal·lacions noves on la migració original creava `caption`/`order`.
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasColumn('carousels', 'activo')) {
            Schema::table('carousels', function (Blueprint $table) {
                $table->boolean('activo')->default(true);
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('carousels', 'activo')) {
            Schema::table('carousels', function (Blueprint $table) {
                $table->dropColumn('activo');
            });
        }
    }
};
