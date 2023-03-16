<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResendDonorPhotoTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resend_donor_photo', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('donor_id');
            $table->string('token');
            $table->dateTime('expired_at');
            $table->enum('status', ['valid', 'finished'])->default('valid');
            $table->timestamps();

            $table->foreign('donor_id')->references('id')->on('donors');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('resend_donor_photo', function (Blueprint $table) {
           $table->dropIfExists();
        });
    }
}
