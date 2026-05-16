<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('programs', function (Blueprint $create) {
            $create->id();
            $create->string('title');
            $create->text('description');
            $create->string('icon')->nullable();
            $create->string('image')->nullable();
            $create->boolean('is_active')->default(true);
            $create->integer('order')->default(0);
            $create->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('programs');
    }
};
