<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDonationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('donations', function (Blueprint $table) {
            $table->id();
            $table->string('payments_donation_id')->nullable()->comment('Donation ID on payments app');
            $table->string('tracking_code')->nullable();
            $table->string('simma_payment_id')->nullable();
            $table->unsignedBigInteger('donor_id');
            $table->unsignedBigInteger('donor_media_id')->nullable();
            $table->unsignedBigInteger('event_id')->nullable();
            $table->integer('child_quantity');
            $table->float('child_amount',10,2)->nullable();
            $table->float('amount', 10,2)->default(0);
            $table->enum('revelation_type', ['fisical', 'digital']);
            $table->float('revelation_amount', 10,2)->nullable();
            $table->enum('donation_status',['pendent', 'processing','paid', 'refused'])->default('pendent');
            $table->longText('extra_info')->nullable();
            $table->timestamp('payment_date')->nullable();
            $table->timestamps();

            $table->foreign('donor_id')->references('id')->on('donors');
            $table->foreign('donor_media_id')->references('id')->on('donor_medias');
            $table->foreign('event_id')->references('id')->on('events');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('donations');
    }
}
