<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterDonorMediasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('donor_medias', function (Blueprint $table) {
            $table->unsignedBigInteger('donation_id')->after('donor_id')->nullable();
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
        Schema::table('donor_medias', function (Blueprint $table) {
            $table->dropForeign('donor_medias_donation_id_foreign');
            $table->dropColumn('donation_id');
        });
    }
}
