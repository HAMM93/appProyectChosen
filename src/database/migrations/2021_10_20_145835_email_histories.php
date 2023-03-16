<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class EmailHistories extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('email_histories', function (Blueprint $table) {
            $table->id();
            $table->string('to_address')->nullable();
            $table->string('from_address')->nullable();
            $table->text('subject')->nullable();
            $table->longText('body')->nullable();
            $table->text('message_id')->nullable();
            $table->timestamp('opened')->nullable();
            $table->timestamp('delivered')->nullable();
            $table->timestamp('complaint')->nullable();
            $table->timestamp('bounced')->nullable();
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
        Schema::dropIfExists('email_histories');
    }
}
