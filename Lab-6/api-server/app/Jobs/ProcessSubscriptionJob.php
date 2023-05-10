<?php

namespace App\Jobs;

use App\Jobs\Options\ProcessSubscriptionJobOptions;
use App\Models\Subscription;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class ProcessSubscriptionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(private readonly ProcessSubscriptionJobOptions $options)
    {

    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Subscription::query()
            ->where('id', '=', $this->options->subscription->id)
            ->update(['next_billing_time' => DB::raw('TIMESTAMPADD(month, 1, next_billing_time)')]);
    }
}
