<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterDonationChildrenTableAddRevelationIdField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('donation_children', function (Blueprint $table) {
            $table->unsignedBigInteger('revelation_group_id')->after('donation_id')->nullable();
            $table->unsignedBigInteger('revelation_individual_id')->after('revelation_group_id')->nullable();

            $table->foreign('revelation_group_id')->references('id')->on('revelations');
            $table->foreign('revelation_individual_id')->references('id')->on('revelations');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('donation_children', function (Blueprint $table) {
            $table->dropForeign('donation_children_revelation_group_id_foreign');
            $table->dropForeign('donation_children_revelation_individual_id_foreign');

            $table->dropColumn(['revelation_group_id','revelation_individual_id']);
        });
    }
}
