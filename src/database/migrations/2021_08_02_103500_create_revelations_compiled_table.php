<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRevelationsCompiledTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('revelations_compiled', function (Blueprint $table) {
            $table->id();
            $table->string('file')->nullable()->comment('name of merged pdf processed with groups of revelations');
            $table->string('process_status')->nullable()->default('0')
                ->comment('0=>processing; 1=>processed; 2=>failed');
            $table->text('process_status_details')->nullable();
            $table->longText('extra_info')->nullable();
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
        Schema::dropIfExists('revelations_compiled');
    }
}
