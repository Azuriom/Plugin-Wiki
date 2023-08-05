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
        Schema::table('wiki_categories', function (Blueprint $table) {
            $table->unsignedInteger('parent_id')->nullable()->after('position');

            $table->foreign('parent_id')->references('id')->on('wiki_categories');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('wiki_categories', function (Blueprint $table) {
            $table->dropColumn('parent_id');
        });
    }
};
