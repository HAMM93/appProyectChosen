<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSimmaPartnersChecksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('simma_partners_checks', function (Blueprint $table) {
            $table->id();
            $table->integer('partnerID');
            $table->integer('donor_id')->nullable();
            $table->integer('status')->comment('0=>not-sync; 1=>synced; 2=>in-queue; 3=>sync-fail')->default('0');
            $table->longText('comment')->nullable();
            $table->longText('exta_info')->nullable();
            $table->timestamp('sync_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('simma_partners_checks');
    }
}
