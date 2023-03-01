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
        Schema::table('bookings', function (Blueprint $table) {
            $table->unsignedInteger('price')->nullable();

            $table->foreignIdFor(\App\Models\PersonAddress::class, 'person_address_id')
                ->nullable()
                ->constrained('person_addresses');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn('price');
            $table->dropForeignIdFor(\App\Models\PersonAddress::class, 'person_address_id');
            $table->dropColumn('person_address_id');
        });
    }
};
