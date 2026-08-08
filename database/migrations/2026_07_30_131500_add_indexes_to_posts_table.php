<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->index('visibility');
            $table->index('category');
            $table->index('created_at');
            $table->index('visitas');
            $table->index(['visibility', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            $table->dropIndex(['visibility']);
            $table->dropIndex(['category']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['visitas']);
            $table->dropIndex(['visibility', 'created_at']);
        });
    }
};
