<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterEventTableAddStatusField extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->integer('max_participants')->default(15);

            $table->integer('status')
                ->comment('0=finished; 1=active')
                ->nullable();

            $table->integer('status_participante')
                ->comment('0=close; 1=open')
                ->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->dropColumn(['max_participants','status','status_participante']);
        });
    }
}
