<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDonationSimmapledgesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('donation_simmapledges', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('donation_id');
            $table->integer('pledge_id');
            $table->timestamps();

            $table->foreign('donation_id')->references('id')->on('donations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('donation_simmapledges');
    }

}
