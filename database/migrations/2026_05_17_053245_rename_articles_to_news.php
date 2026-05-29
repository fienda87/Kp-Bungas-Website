<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        Schema::rename('articles', 'news');

        Schema::table('news', function (Blueprint $table) use ($driver) {
            if (Schema::hasColumn('news', 'user_id')) {
                if ($driver === 'mysql') {
                    $table->dropForeign('articles_user_id_foreign');
                } else {
                    $table->dropForeign(['user_id']);
                }

                $table->dropColumn('user_id');
            }

            $table->renameColumn('featured_image', 'thumbnail');
        });

        Schema::table('news', function (Blueprint $table) use ($driver) {
            if ($driver === 'mysql') {
                $table->dropForeign('articles_category_id_foreign');
            } else {
                $table->dropForeign(['category_id']);
            }

            $table->string('category')->nullable()->after('slug');
        });

        DB::table('news')->update([
            'category' => DB::raw('CAST(category_id AS CHAR)'),
        ]);

        Schema::table('news', function (Blueprint $table) {
            $table->dropColumn(['category_id', 'excerpt']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('news', function (Blueprint $table) {
            $table->unsignedBigInteger('category_id')->nullable()->after('slug');
            $table->renameColumn('thumbnail', 'featured_image');
            $table->text('excerpt')->nullable();
            $table->foreignId('user_id')->nullable();
            $table->dropColumn('category');
        });

        Schema::rename('news', 'articles');
    }
};
