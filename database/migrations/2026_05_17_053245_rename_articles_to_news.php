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
        Schema::rename('articles', 'news');

        Schema::table('news', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
            
            $table->dropForeign(['category_id']);
            $table->renameColumn('category_id', 'category');
            
            $table->renameColumn('featured_image', 'thumbnail');
            $table->dropColumn('excerpt');
        });

        Schema::table('news', function (Blueprint $table) {
            $table->string('category')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->renameColumn('thumbnail', 'featured_image');
            $table->text('excerpt')->nullable();
            $table->renameColumn('category', 'category_id');
            $table->foreignId('user_id')->nullable();
        });

        Schema::rename('news', 'articles');
    }
};
