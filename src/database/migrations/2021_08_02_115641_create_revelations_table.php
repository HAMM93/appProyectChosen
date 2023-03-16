<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateRevelationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('revelations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('revelation_compiled_id')->nullable();
            $table->integer('type')->nullable()->comment('1=> individual revelation; 2=> grouped revelations');
            $table->string('file')->nullable()->comment('name of the revelation pdf file');
            $table->string('compiled_status')->nullable()->default('not-processed')
                ->comment('0=> not-processed; 1=>todo; 2=>in-queue; 3=>processed');
            $table->timestamp('compiled_at')->nullable()->comment('date at revelation PDF was compiled');
            $table->longText('extra_info')->nullable();
            $table->timestamps();

            $table->foreign('revelation_compiled_id')->references('id')->on('revelations_compiled');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');
        Schema::dropIfExists('revelations');
        DB::statement('SET FOREIGN_KEY_CHECKS = 1');
    }
}
