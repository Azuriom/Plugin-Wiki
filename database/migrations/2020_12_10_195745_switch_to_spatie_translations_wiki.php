<?php

use Azuriom\Plugin\Wiki\Models\Category;
use Azuriom\Plugin\Wiki\Models\Page;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class SwitchToSpatieTranslationsWiki extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('wiki_categories', function (Blueprint $table) {
            $table->text('name')->change();
        });

        Schema::table('wiki_pages', function (Blueprint $table) {
            $table->text('title')->change();
            $table->text('content')->change();
        });

        $locale = App::getLocale();

        $rawModels = DB::table('wiki_categories')->get();
        foreach ($rawModels as $key => $category) {
            $category = Category::find($category->id);
            $category
                ->setTranslation('name', $locale, $rawModels[$key]->name)
                ->save();
        }

        $rawModels = DB::table('wiki_pages')->get();
        foreach ($rawModels as $key => $page) {
            $page = Page::find($page->id);
            $page
                ->setTranslation('title', $locale, $rawModels[$key]->title)
                ->setTranslation('content', $locale, $rawModels[$key]->content)
                ->save();
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
