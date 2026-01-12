<?php

namespace App\Commands;

use Illuminate\Console\Scheduling\Schedule;
use LaravelZero\Framework\Commands\Command;
use Illuminate\Support\Facades\File;
use OpenAI;
use Symfony\Component\Process\Process;

class ReviewCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'review';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Review staged git changes using AI';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $configPath = $_SERVER['HOME'] . '/.ai-review/config.json';
        if (!File::exists($configPath)) {
            $this->error('No API Key found. Please run: cgm-ai-review config');
            return;
        }

        $config = json_decode(File::get($configPath), true);
        // $client = OpenAI::client($config['api_key']);
        $client = OpenAI::factory()
                        ->withApiKey($config['api_key'])
                        ->withBaseUri('https://api.groq.com/openai/v1')
                        ->make();

        $this->info('🔍 Fetching staged changes...');
        $process = new Process(['git', 'diff', '--cached']);
        $process->run();

        if (!$process->isSuccessful()) {
            $this->error('Git diff failed. Are you in a git repository?');
            return;
        }

        $diff = $process->getOutput();

        if (empty($diff)) {
            $this->warn('No staged changes found. Use "git add" first.');
            return;
        }

        $this->info('🤖 AI is reviewing your code...');
        
        try {
            $result = $client->chat()->create([
                'model' => 'llama-3.3-70b-versatile',
                'messages' => [
                    ['role' => 'system', 'content' => 'You are a senior developer. Review the git diff for bugs, security risks, and performance. Be concise.'],
                    ['role' => 'user', 'content' => "Review this git diff:\n\n" . $diff],
                ],
            ]);

            $this->line("\n--- AI REVIEW RESULTS ---\n");
            $this->info($result->choices[0]->message->content);
            $this->line("\n-------------------------\n");

        } catch (\Exception $e) {
            $this->error('AI Request failed: ' . $e->getMessage());
        }
    }

    /**
     * Define the command's schedule.
     */
    public function schedule(Schedule $schedule): void
    {
        // $schedule->command(static::class)->everyMinute();
    }
}
