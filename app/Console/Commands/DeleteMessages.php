<?php

namespace App\Console\Commands;

use App\Models\Message;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class DeleteMessages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'msg:delete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete messages that are 24 hours old or more';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        /**
         * Delete all messages that are 24 hours old or more
         */
        Message::where('created_at', '<=', Carbon::now()->subDays(1)->toDateTimeString())
        ->delete();
    }
}
