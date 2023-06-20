<?php
declare(strict_types=1);

namespace App\Jobs\Options;

use App\Models\Subscription;

class ProcessSubscriptionJobOptions
{
    public function __construct(
        public readonly Subscription $subscription
    )
    {
    }

}
