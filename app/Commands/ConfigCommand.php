<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use Illuminate\Support\Facades\File;

class ConfigCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'config';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set your personal OpenAI API Key';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $key = $this->ask('Please enter your OpenAI API Key');

        if (empty($key)) {
            $this->error('API Key cannot be empty.');
            return;
        }

        // Create a hidden directory in the user's home folder
        $configDir = $_SERVER['HOME'] . '/.ai-review';
        if (!File::exists($configDir)) {
            File::makeDirectory($configDir);
        }

        File::put($configDir . '/config.json', json_encode(['api_key' => $key]));

        $this->info('✅ API Key saved successfully to ' . $configDir . '/config.json');
    }
}
