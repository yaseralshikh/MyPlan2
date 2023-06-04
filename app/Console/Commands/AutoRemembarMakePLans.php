<?php

namespace App\Console\Commands;
use App\Mail\RememberToMakePlans;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Console\Command;

class AutoRemembarMakePLans extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'auto:remembarmakepLans';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = User::whereNotNull('email_verified_at')->get();

        if ($users->count() > 0) {
            foreach ($users as $user) {
                Mail::to($user)->send(new RememberToMakePlans($user));
            }
        }

        return Command::SUCCESS;
    }
}
