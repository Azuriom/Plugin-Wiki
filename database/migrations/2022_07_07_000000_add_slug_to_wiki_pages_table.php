<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('wiki_pages', function (Blueprint $table) {
            $table->string('slug')->unique()->nullable()->after('title');
        });

        foreach (DB::table('wiki_pages')->get() as $page) {
            DB::table('wiki_pages')
                ->where('id', $page->id)
                ->update(['slug' => $page->id]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wiki_pages', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
