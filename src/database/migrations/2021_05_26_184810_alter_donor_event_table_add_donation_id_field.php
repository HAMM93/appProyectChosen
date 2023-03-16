<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterDonorEventTableAddDonationIdField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('donor_event', function (Blueprint $table) {
            $table->unsignedBigInteger('donation_id')
                ->after('donor_id')->nullable();
            $table->unsignedBigInteger('donation_child_id')
                ->after('donation_id')->nullable();

            $table->foreign('donation_id')->references('id')->on('donations');
            $table->foreign('donation_child_id')->references('id')->on('donation_children');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('donor_event', function (Blueprint $table) {
            $table->dropForeign('donor_event_donation_id_foreign');
            $table->dropForeign('donor_event_donation_child_id_foreign');
            
            $table->dropColumn(['donation_id', 'donation_child_id']);
        });
    }
}
