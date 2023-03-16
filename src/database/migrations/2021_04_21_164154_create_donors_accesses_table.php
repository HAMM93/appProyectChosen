<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDonorsAccessesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('donors_accesses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('donor_id');
            $table->string('access_token');
            $table->timestamp('used_at')->nullable();
            $table->string('used_origin')->nullable()->comment('IP de quem usou');
            $table->integer('status')->default(1)->comment('1=wating for use; 2=used');
            $table->timestamp('expire_at')->nullable();
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
        Schema::dropIfExists('donors_accesses');
    }
}
