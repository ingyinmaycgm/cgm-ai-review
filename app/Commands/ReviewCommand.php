<?php

namespace App\Commands;

use App\Services\AIReviewService;
use App\Services\GitService;
use Illuminate\Support\Facades\File;
use LaravelZero\Framework\Commands\Command;
use OpenAI;
use Exception;

/**
 * Class ReviewCommand
 *
 * Orchestrates the AI-powered code review process. This command fetches 
 * staged git changes, communicates with the Groq AI service via the 
 * AIReviewService, and outputs architectural and quality feedback.
 *
 * @package App\Commands
 */
class ReviewCommand extends Command
{
    protected $signature = 'review';
    protected $description = 'Review staged git changes using AI';

    /**
     * Execute the console command.
     *
     * This method acts as the controller for the review process:
     * 1. Resolves dependencies and configuration.
     * 2. Retrieves the current git diff from the GitService.
     * 3. Sends the diff to the AIReviewService for analysis.
     * 4. Renders the AI's response to the terminal.
     *
     * @param GitService $gitService Injected service to handle version control operations.
     * @return void
     */
    public function handle(GitService $gitService)
    {
        try {
            $aiService = $this->resolveAiService();

            $this->info('🔍 Fetching staged changes...');
            $diff = $gitService->getStagedDiff();

            if (empty($diff)) {
                $this->warn('No staged changes found. Use "git add" first.');
                return;
            }

            $this->info('🤖 AI is reviewing your code...');
            $review = $aiService->getReview($diff);

            $this->line("\n--- AI REVIEW RESULTS ---\n");
            $this->info($review);
            $this->line("\n-------------------------\n");

        } catch (Exception $e) {
            $this->error('Error: ' . $e->getMessage());
        }
    }

    /**
     * Resolves and initializes the AI Service with the user's local configuration.
     *
     * Loads the API credentials from the ~/.ai-review/config.json file,
     * instantiates the OpenAI factory with the Groq base URI, and 
     * returns a configured instance of AIReviewService.
     *
     * @throws Exception If the configuration file is missing or invalid.
     * @return AIReviewService
     */
    private function resolveAiService(): AIReviewService
    {
        $configPath = $_SERVER['HOME'] . '/.ai-review/config.json';
        
        if (!File::exists($configPath)) {
            throw new Exception('No API Key found. Please run: cgm-ai-review config');
        }

        $config = json_decode(File::get($configPath), true);

        $client = OpenAI::factory()
            ->withApiKey($config['api_key'])
            ->withBaseUri('https://api.groq.com/openai/v1')
            ->make();

        return new AIReviewService($client);
    }
}
