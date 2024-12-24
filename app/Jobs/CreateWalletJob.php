<?php

namespace App\Jobs;

use App\Models\User;
use App\Models\Wallet;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class CreateWalletJob implements ShouldQueue
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
        Log::info("---JOB.CREATE_WALLET INIT...---");
        $userId=$this->user->id;
        Wallet::create([
            "user_id"=>$userId
        ]);
        Log::info("---JOB.CREATE_WALLET SUCCESS...---");
    }
}
