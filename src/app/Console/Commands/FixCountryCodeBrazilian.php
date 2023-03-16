<?php

namespace App\Console\Commands;

use App\Models\Donor;
use Illuminate\Console\Command;

class FixCountryCodeBrazilian extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:fix-country-code-brazilian';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     */
    public function handle()
    {
        $model = Donor::select('id', 'phone')->get();

        foreach ($model as $value) {
            $new_phone = '55' . $this->sanitize($value->phone);
            $value->update(['phone' => $new_phone]);
        }
    }

    public function sanitize($phone)
    {
        return str_replace([' ', '-', '(', ')', '+', '55'], '', $phone);
    }
}
