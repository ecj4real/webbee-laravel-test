<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCinemaSchema extends Migration
{
    /**
    # Create a migration that creates all tables for the following user stories

    For an example on how a UI for an api using this might look like, please try to book a show at https://in.bookmyshow.com/.
    To not introduce additional complexity, please consider only one cinema.

    Please list the tables that you would create including keys, foreign keys and attributes that are required by the user stories.

    ## User Stories

     **Movie exploration**
     * As a user I want to see which films can be watched and at what times
     * As a user I want to only see the shows which are not booked out

     **Show administration**
     * As a cinema owner I want to run different films at different times
     * As a cinema owner I want to run multiple films at the same time in different locations

     **Pricing**
     * As a cinema owner I want to get paid differently per show
     * As a cinema owner I want to give different seat types a percentage premium, for example 50 % more for vip seat

     **Seating**
     * As a user I want to book a seat
     * As a user I want to book a vip seat/couple seat/super vip/whatever
     * As a user I want to see which seats are still available
     * As a user I want to know where I'm sitting on my ticket
     * As a cinema owner I dont want to configure the seating for every show
     */

     
    public function up()
    {
        Schema::create('films', function ($table) {
            $table->id();
            $table->string('name');
            $table->string('price');
            $table->timestamps();
        });

        Schema::create('shows', function ($table) {
            $table->id();
            $table->dateTime('show_time');
            $table->string('price');
            $table->timestamps();
        });

        Schema::create('cinemas', function ($table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('name');
            $table->string('location');
            $table->unsignedBigInteger('seats_count');
            $table->timestamps();
        });

        Schema::create('seats', function ($table) {
            $table->id();
            $table->unsignedBigInteger('cinema_id');
            $table->unsignedBigInteger('user_id');
            $table->enum('type', ['vip seat', 'couple seat', 'super vip', 'whatever'])->default('whatever');
            $table->string('percentage_premium')->nullable();
            $table->timestamps();
            $table->foreign('cinema_id')->references('id')->on('cinemas')->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });

        Schema::create('tickets', function ($table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('seat_id');
            $table->unsignedBigInteger('film_id')->nullable();
            $table->unsignedBigInteger('show_id')->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            $table->foreign('seat_id')->references('id')->on('seats')->cascadeOnDelete();
            $table->foreign('film_id')->references('id')->on('films')->onDelete('set null');
            $table->foreign('show_id')->references('id')->on('shows')->onDelete('set null');
        });

        Schema::create('payments', function ($table) {
            $table->id();
            $table->unsignedBigInteger('ticket_id');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('ticket_id')->references('id')->on('tickets')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
        Schema::dropIfExists('tickets');
        Schema::dropIfExists('seats');
        Schema::dropIfExists('cinemas');
        Schema::dropIfExists('shows');
        Schema::dropIfExists('films');
    }
}
