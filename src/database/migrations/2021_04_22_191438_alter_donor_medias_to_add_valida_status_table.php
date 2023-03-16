<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterDonorMediasToAddValidaStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('donor_medias', function (Blueprint $table) {
            $table->string('validation_status')
                ->after('status')
                ->nullable()
                ->comment('reproved | approved | pending')
                ->default('pending');
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
            $table->dropColumn('validation_status');
        });
    }
}
