<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MakeFillable extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'model:fillable {model}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generates array $fillable into Model by table structure';

    protected $notFillable = ['id', 'created_at', 'updated_at', 'deleted_at'];

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
     */
    public function handle()
    {
        try {
            $modelName = 'App\Models\\' . $this->argument('model');

            $model = new $modelName();

            $columns = DB::select("SHOW COLUMNS FROM " . $model->getTable());

            $columns = collect($columns)->map(function ($item) {
                if (!in_array($item->Field, $this->notFillable))
                    return $item->Field;
            })->toArray();

            $new_col = [];
            foreach ($columns as $column) {
                if (!is_null($column))
                    $new_col[] = $column;
            }

            $new_col = collect($new_col);
            $reflection = new \ReflectionClass($model);

            $file = file_get_contents($reflection->getFileName());
            $file = str_replace('{{fillable}}', $new_col, $file);
            $file = str_replace('//', '', $file);
            file_put_contents($reflection->getFileName(), $file);
            $this->info('Fillable field has been filled!');
        } catch (\Exception $exception) {
            $this->error('Error: ' . $exception->getMessage());
        }
    }
}
