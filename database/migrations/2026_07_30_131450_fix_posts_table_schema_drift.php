<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * The original create_posts_table migration defines `category_id` and `tags`,
 * but the application code (Post model, controllers) has always used `category`
 * and `visitas`, which were added directly to the database out-of-band. This
 * migration reconciles the schema for any environment, whichever state it's in.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            if (Schema::hasColumn('posts', 'category_id') && !Schema::hasColumn('posts', 'category')) {
                $table->renameColumn('category_id', 'category');
            }

            if (!Schema::hasColumn('posts', 'visitas')) {
                $table->unsignedInteger('visitas')->default(0)->after('visibility');
            }

            if (!Schema::hasColumn('posts', 'category')) {
                $table->integer('category')->default(0)->after('visibility');
            }
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->text('content')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('posts', function (Blueprint $table) {
            if (Schema::hasColumn('posts', 'category') && !Schema::hasColumn('posts', 'category_id')) {
                $table->renameColumn('category', 'category_id');
            }

            if (Schema::hasColumn('posts', 'visitas')) {
                $table->dropColumn('visitas');
            }
        });

        Schema::table('posts', function (Blueprint $table) {
            $table->text('content')->nullable(false)->change();
        });
    }
};
