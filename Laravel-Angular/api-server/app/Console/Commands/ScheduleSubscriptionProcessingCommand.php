<?php

namespace App\Console\Commands;

use App\Jobs\Options\ProcessSubscriptionJobOptions;
use App\Jobs\ProcessSubscriptionJob;
use App\Models\Subscription;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class ScheduleSubscriptionProcessingCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:process';


    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Process users subscriptions';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle(): void
    {
        Log::info('Subscription process started');
        $subscriptions = Subscription::query()
            ->where('next_billing_time', '<', Carbon::now())
            ->get();

        foreach ($subscriptions as $subscription) {
            ProcessSubscriptionJob::dispatch(new ProcessSubscriptionJobOptions($subscription))
                ->onQueue('process-subscriptions');
        }

        Log::info('Finished subscriptions process');
    }
}
