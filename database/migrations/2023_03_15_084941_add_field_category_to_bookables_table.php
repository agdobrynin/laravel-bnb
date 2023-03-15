<?php

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
        Schema::table('bookables', function (Blueprint $table) {
            $table->foreignIdFor(\App\Models\BookableCategory::class, 'bookable_category_id')
                ->constrained('bookable_categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bookables', function (Blueprint $table) {
            $table->dropForeignIdFor(\App\Models\BookableCategory::class, 'bookable_category_id');
            $table->dropColumn('bookable_category_id');
        });
    }
};
