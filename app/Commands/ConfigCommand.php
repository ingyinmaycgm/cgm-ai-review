<?php

namespace App\Commands;

use LaravelZero\Framework\Commands\Command;
use Illuminate\Support\Facades\File;

/**
 * Class ConfigCommand
 * * Handles the initial setup and configuration of the CGM AI Review tool.
 * This command prompts the user for their Groq API key and persists it
 * to a local JSON file in the user's home directory.
 * * @package App\Commands
 */
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
    protected $description = 'Set your personal Groq AI API Key';

    /**
     * Execute the console command.
     * * This method manages the interaction with the user to retrieve the API key,
     * validates the input, ensures the configuration directory exists, 
     * and saves the credentials securely.
     *
     * @return void
     */
    public function handle()
    {
        $key = $this->ask('Please enter your Groq AI API Key');

        if (empty($key)) {
            $this->error('API Key cannot be empty.');
            return;
        }

        $configDir = $_SERVER['HOME'] . '/.ai-review';
        if (!File::exists($configDir)) {
            File::makeDirectory($configDir);
        }

        File::put($configDir . '/config.json', json_encode(['api_key' => $key]));

        $this->info('✅ API Key saved successfully to ' . $configDir . '/config.json');
    }
}
