<?php

namespace App\Jobs\User;

use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;
use Random\RandomException;

class SendVerificationCodeToUserJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public int $userId) {}

    /**
     * Execute the job.
     *
     * @throws RandomException
     */
    public function handle(): void
    {
        $user = User::find($this->userId);
        $user->email_verification_code = random_int(100000, 999999);
        $user->email_verification_expires_at = now()->addMinutes(10);
        $user->save();
        Mail::raw('Your verification code-'.$user->email_verification_code, function ($message) use ($user) {
            $message->to($user->email)->subject('Verification_code');
        });
    }
}
