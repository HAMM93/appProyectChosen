<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDonationChildrenTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('donation_children', function (Blueprint $table) {
            $table->id();
            $table->string('simma_child_id');
            $table->unsignedBigInteger('donation_id');
            $table->string('child_name');
            $table->string('child_age');
            $table->string('child_city');
            $table->string('child_photo')->nullable();
            $table->string('letter_photo')->nullable();
            $table->longText('extra_info')->nullable();
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
        Schema::dropIfExists('donation_children');
    }
}
