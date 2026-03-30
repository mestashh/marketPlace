<?php

namespace App\Jobs\User;

use App\Mail\User\VerificationCodeToUserMail;
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
        $code = random_int(100000, 999999);
        $user->update([
            'email_verification_code' => $code,
            'email_verification_expires_at' => now()->addMinutes(10),
        ]);
        Mail::to($user->email)->send(new VerificationCodeToUserMail($code));
    }
}
