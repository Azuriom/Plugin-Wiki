<?php

use Azuriom\Plugin\Wiki\Models\Page;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wiki_pages', function (Blueprint $table) {
            $table->string('slug')->unique()->nullable()->after('title');
        });

        foreach (Page::all() as $page) {
            $page->update(['slug' => $page->id]);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('wiki_pages', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
};
