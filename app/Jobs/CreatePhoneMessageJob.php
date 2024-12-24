<?php

namespace App\Jobs;

use App\Models\PhoneMessage;
use App\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class CreatePhoneMessageJob implements ShouldQueue
{
    use Queueable;
    protected $user;

    /**
     * Create a new job instance.
     */
    public function __construct(User $user)
    {
        $this->user=$user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        Log::info("---JOB.CREATE_PHONE_MESSAGE INIT".$this->user->phoneNumber."---");
        $otpCode=rand(1000,9999);
        PhoneMessage::create([
            "user_id"=>$this->user->id,
            "otpCode"=>$otpCode,
            "phoneNumber"=>$this->user->phoneNumber,
            "expires_at"=>now()->addMinutes(5)
        ]);
        Log::info("---JOB.CREATE_PHONE_MESSAGE SUCCESS---");

    }
}
