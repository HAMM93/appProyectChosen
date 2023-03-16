<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSimmaIntegrationPaymentTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('simma_integration_payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('donor_id');
            $table->unsignedBigInteger('donation_id');
            $table->text('card');
            $table->timestamps();

            $table->foreign('donor_id')->references('id')->on('donors');
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
        Schema::dropIfExists('simma_integration_payment_transactions');
    }
}
