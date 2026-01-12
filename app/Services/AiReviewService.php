<?php

namespace App\Services;

use OpenAI\Client;

/**
 * Class AIReviewService
 *
 * This service acts as the bridge between the application and the Groq/OpenAI API.
 * It is responsible for constructing the architectural persona (system prompt),
 * preparing the payload, and processing the AI's response to provide
 * high-quality code reviews.
 *
 * @package App\Services
 */
class AIReviewService
{
    /**
     * Create a new AIReviewService instance.
     *
     * @param Client $client The configured OpenAI/Groq API client.
     */
    public function __construct(private Client $client) {}

    /**
     * Sends the git diff to the AI and retrieves a structured architectural review.
     *
     * This method triggers a chat completion request using the Llama 3.3 70B model.
     * It combines a strict architectural system prompt with the user's 
     * code changes to generate feedback on bugs, security, and standards.
     *
     * @param string $diff The raw git diff string to be analyzed.
     * @return string The formatted review text returned by the AI.
     */
    public function getReview(string $diff): string
    {
        return $this->client->chat()->create([
            'model' => 'llama-3.3-70b-versatile',
            'messages' => [
                ['role' => 'system', 'content' => $this->getSystemPrompt()],
                ['role' => 'user', 'content' => "Review this git diff:\n\n" . $diff],
            ],
        ])->choices[0]->message->content;
    }

    /**
     * Defines the "Elite Polyglot Software Architect" persona and ruleset.
     *
     * The system prompt contains the core intelligence of the tool, enforcing:
     * 1. Logic and Bug detection (DB Transactions, N+1 queries).
     * 2. Security (Secret detection, Debug cleanup).
     * 3. Layered Architecture (Controller vs Service vs DAO).
     * 4. Industry Standards (SOLID, PSR-12, Microsoft Conventions).
     *
     * @return string The full system prompt text.
     */
    private function getSystemPrompt(): string
    {
        return <<<PROMPT
            You are an Elite Polyglot Software Architect. Review the provided git diff strictly against these rules:

            ### 1. BUG & LOGIC CHECK (CRITICAL)
            - **Bug Detection:** Identify potential logic errors or conditions that will cause a crash.
            - **Transactions:** Verify all DB write operations (Insert/Update/Delete) are wrapped in a Transaction.
            - **Query Optimization:** Flag any Database queries inside a loop (N+1 issues).
            - **Hardcoded Values:** Flag strings/numbers that should be in config/constants.

            ### 2. SECURITY & SECRETS
            - **No Secrets:** Search for API keys, DB passwords, Tokens, or Private Keys.
            - **Debug Cleanup:** Ensure NO debug statements remain (dd, var_dump, console.log, print_r).
            - **Security:** Look for SQL Injection, XSS, or hardcoded secrets.

            ### 3. ARCHITECTURE & CODING STANDARDS
            - **Controllers:** Validate input only. No business logic or database queries.
            - **Service Layer:** All business logic must live here.
            - **DAO/DTO Access:** No database access allowed outside of DAO/Repository/DTO layers.
            - **Documentation:** Every new function/method MUST have doc-blocks.
            - **Comments:** Complicated logic needs comments.
            - **EOF:** Newly created files MUST end with a newline.

            ### 4. LANGUAGE SPECIFIC STANDARDS
            - PHP: PSR-12, Java: Google Java Style, C#: Microsoft Conventions.
            - SOLID: Strictly enforce SRP and Open/Closed principles.

            ### OUTPUT FORMAT:
            - Start with: "Language(s) Detected: [List]"
            - Use [🛑 BUG/SECURITY], [🏛️ ARCHITECTURE], [🧹 CLEAN CODE], [⚡ OPTIMIZATION], [💡 SUGGESTION].
            PROMPT;
    }
}
