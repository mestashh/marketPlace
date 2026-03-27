<?php

namespace App\Listeners\User;

use App\Events\User\GenerateEmailVerification;
use App\Jobs\User\SendVerificationCodeToUserJob;

class SendVerificationCodeToUser
{
    /**
     * Create the event listener.
     */
    public function __construct() {}

    /**
     * Handle the event.
     */
    public function handle(GenerateEmailVerification $event): void
    {
        SendVerificationCodeToUserJob::dispatch($event->userId);
    }
}
