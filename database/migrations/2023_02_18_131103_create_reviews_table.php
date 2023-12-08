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
        Schema::create('reviews', function (Blueprint $table) {
            $table->uuid('id')->nullable(false)->primary();
            $table->timestamps();
            $table->unsignedTinyInteger('rating');
            $table->text('description');

            $table->foreignIdFor(\App\Models\Bookable::class, 'bookable_id')
                ->constrained('bookables');

            $table->foreignIdFor(\App\Models\Booking::class, 'booking_id')
                ->nullable()
                ->constrained('bookings');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reviews');
    }
};
