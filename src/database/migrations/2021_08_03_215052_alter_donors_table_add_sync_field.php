<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterDonorsTableAddSyncField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('donors', function (Blueprint $table) {
            $table->string('document')->after('name')->nullable();
            $table->string('simma_sync_status')->after('status')->nullable();
            $table->timestamp('simma_sync_date')->after('simma_sync_status')->nullable();
            $table->longText('simma_sync_details')->after('simma_sync_date')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('donors', function (Blueprint $table) {
            $table->dropColumn(['simma_sync_status','simma_sync_date','simma_sync_details','document']);
        });
    }
}
