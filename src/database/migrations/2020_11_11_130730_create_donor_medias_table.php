<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDonorMediasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('donor_medias', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('donor_id');
            $table->string('media_source');
            $table->enum('media_type',['photo','video'])->default('photo');
            $table->enum('status', ['active','inactive'])->default('active');
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
        Schema::dropIfExists('donor_medias');
    }
}
