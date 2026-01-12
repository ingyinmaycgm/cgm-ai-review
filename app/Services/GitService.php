<?php

namespace App\Services;

use Symfony\Component\Process\Process;
use Exception;

/**
 * Class GitService
 *
 * This service is responsible for interacting with the local Git binary.
 * It encapsulates the logic required to extract code changes from the 
 * version control system so they can be processed by the AI Reviewer.
 *
 * @package App\Services
 */
class GitService
{
    /**
     * Fetches the staged changes (git diff --cached).
     *
     * This method executes a synchronous process to retrieve the differences 
     * between the index (staged area) and the last commit. It is used to 
     * isolate exactly what the developer intends to commit.
     *
     * @return string The raw diff output from the git command.
     * @throws Exception If the git command fails (e.g., if the directory is not a git repo).
     */
    public function getStagedDiff(): string
    {
        $process = new Process(['git', 'diff', '--cached']);
        $process->run();

        if (!$process->isSuccessful()) {
            throw new Exception('Git diff failed. Are you in a git repository?');
        }

        return $process->getOutput();
    }
}
