<?php

namespace App\Console\Commands;

use App\Models\Product;
use App\Models\StripeProducts;
use App\Services\Payment\src\PaymentSetup;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class StripeSetupCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'setup:stripe';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set configuration in Stripe account';

    private $stripeClient;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     * @throws \Exception
     */
    public function handle()
    {
        $this->info(Carbon::now() . ' Initializing Payment Provider Setup ... ');

        $payment_setup = new PaymentSetup();

        if ($payment_setup->setup) {

            $this->line(Carbon::now() . ' Provider selected: ' . ucfirst($payment_setup->provider));

            $products = Product::all();

            if (!empty($products)) {
                $this->stripeClient = new \Stripe\StripeClient(
                    config('payment.api_key')
                );

                foreach ($products as $product)
                    $this->createStripeProduct($product);
            } else {
                $this->error(Carbon::now() . ' Products Not Found.');
            }

        } else {
            $this->error(Carbon::now() . ' No Setup needed.');
        }

        $this->info(Carbon::now() . ' Finished Payment Provider Setup ');

        return 0;
    }

    private function createStripeProduct(Product $product): void
    {
        $this->line(Carbon::now() . ' Creating Product and Price: [' . $product->item . ']');
        $stripe_product = $this->stripeClient->products->create([
            'name' => $product->item,
        ]);

        $stripe_price = $this->stripeClient->prices->create([
            'unit_amount' => (int)$product->price,
            'currency' => 'mxn', //TODO :: (Wellington) deve vir do config
            'recurring' => ['interval' => 'month'],
            'product' => $stripe_product->id,
        ]);

        $data = [
            'name' => $product->item,
            'price' => (int)$product->price,
            'product_object_id' => $stripe_product->id,
            'recurring_interval' => 'month',
            'price_object_id' => $stripe_price->id,
            'currency' => 'mxn',
            'product_id' => $product->id
        ];

        try {
            DB::beginTransaction();

            $existing_product = StripeProducts::where(['product_id' => $product->id])->select('id')->get();

            foreach ($existing_product as $item) {
                StripeProducts::destroy($item->id);
            }

            $stripe_product_model = new StripeProducts();
            $stripe_product_model->fill($data)->save();

            DB::commit();
        } catch (\Throwable $exception) {
            DB::rollBack();
            throw new \Exception($exception->getMessage());
        }
    }
}
