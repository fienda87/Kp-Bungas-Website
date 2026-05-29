<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('gallery_photos', function (Blueprint $table) {
            if (!Schema::hasColumn('gallery_photos', 'caption')) {
                $table->string('caption')->nullable()->after('image_path');
            }

            if (!Schema::hasColumn('gallery_photos', 'order')) {
                $table->integer('order')->default(0)->after('caption');
            }
        });
    }

    public function down(): void
    {
        //
    }
};
