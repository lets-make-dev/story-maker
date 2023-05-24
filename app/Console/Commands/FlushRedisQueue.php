<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class FlushRedisQueue extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'queue:redis:flush';

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
        //
        $redis = app()->make('redis');
        $redis->connection()->flushdb();
        $this->info('Redis database has been flushed.');
    }
}
