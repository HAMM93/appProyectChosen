<?php

use App\Services\Payment\src\PaymentSetup;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStripeProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $payment_setup = new PaymentSetup();

        if ($payment_setup->setup) {
            Schema::create('stripe_products', function (Blueprint $table) {
                $table->id();
                $table->string('name', 30);
                $table->string('price', 7)->comment('Value without decimal case, ex: R$ 10,90 will be R$ 1090');
                $table->string('product_object_id', 60);
                $table->string('recurring_interval', 30);
                $table->string('price_object_id', 60);
                $table->string('currency', 3);
                $table->unsignedBigInteger('product_id');
                $table->foreign('product_id')->references('id')->on('products');
                $table->timestamps();
                $table->softDeletes();
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $payment_setup = new PaymentSetup();

        if ($payment_setup->setup) {
            Schema::dropIfExists('stripe_products');
        }
    }
}
