<?php

namespace App\Console;

use App\Console\Commands\SimmaIntegration\{
    SimmaIntegrationBrazil,
    SimmaIntegrationMexico
};
use App\Console\Commands\AddDonorIntoPackage;
use App\Console\Commands\HubSpotLeadsIntegration;
use Carbon\Carbon;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        SimmaIntegrationBrazil::class,
        SimmaIntegrationMexico::class,
        HubSpotLeadsIntegration::class,
        AddDonorIntoPackage::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // Pre-defined variables
        $basepath = getcwd() . DIRECTORY_SEPARATOR . 'app' . DIRECTORY_SEPARATOR . 'Console' . DIRECTORY_SEPARATOR . 'Logs' . DIRECTORY_SEPARATOR;
        $carbonDate = Carbon::now()->format('Y-m-d');

        // -- Make & Send Reports
        // Cron Tab: “At every minute.”
        $logFile = $basepath . $carbonDate . '-sima-integration-donor' . '.log';
        if (!file_exists($logFile)) touch($logFile);

        if (strtoupper(config('simma_integration.version_country')) === 'MEX') {
            $schedule->command(SimmaIntegrationMexico::class)
                ->runInBackground()
                ->withoutOverlapping()
                ->timezone('America/Sao_Paulo')
                ->cron('* * * * *')
                ->appendOutputTo($logFile);
        } elseif (strtoupper(config('simma_integration.version_country')) === 'BRA') {
            $schedule->command(SimmaIntegrationBrazil::class)
                ->runInBackground()
                ->withoutOverlapping()
                ->timezone('America/Sao_Paulo')
                ->cron('* * * * *')
                ->appendOutputTo($logFile);
        }

        $add_donor_into_package_log = $basepath . $carbonDate . 'add-donor-into-package.log';
        if (!file_exists($add_donor_into_package_log)) touch($add_donor_into_package_log);

        $schedule->command(AddDonorIntoPackage::class)
            ->runInBackground()
            ->withoutOverlapping()
            ->timezone('America/Sao_Paulo')
            ->appendOutputTo($add_donor_into_package_log)
            ->everyFiveMinutes();

        // -- Send Donors leads to Hubspot
        // Cron Tab: "At every hour"
        $hubspot_logFile = $basepath . $carbonDate . '-hubspot-integration-lead' . '.log';
        if (!file_exists($hubspot_logFile)) touch($hubspot_logFile);

        $schedule->command(HubSpotLeadsIntegration::class)
            ->runInBackground()
            ->withoutOverlapping()
            ->timezone('America/Sao_Paulo')
            ->hourly()
            ->appendOutputTo($hubspot_logFile);
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
