<?php

declare(strict_types=1);

/*
 * This file is part of the Deuchnord\NoAiBundle bundle.
 *
 * (c) Jérôme Deuchnord <jerome@deuchnord.fr>
 *
 * Licensed under the EUPL-1.2-or-later
 */

namespace Deuchnord\NoAiBundle;

use Symfony\Component\HttpFoundation\Request;

final class LlmDetection implements LlmDetectionInterface
{
    // Source: https://momenticmarketing.com/blog/ai-search-crawlers-bots
    public const CRAWLER_USER_AGENTS = [
        [LlmCrawler::OPEN_AI, ['GPTBot', 'ChatGPT-User', 'OAI-SearchBot']],
        [LlmCrawler::ANTHROPIC, ['anthropic-ai', 'ClaudeBot', 'claude-web']],
        [LlmCrawler::PERPLEXITY, ['PerplexityBot', 'Perplexity-User']],
        [LlmCrawler::GOOGLE, ['Google-Extended']],
        [LlmCrawler::AMAZON, ['Amazonbot']], // See caveats in README.md
        [LlmCrawler::APPLE, ['Applebot-Extended']],
        [LlmCrawler::BYTEDANCE, ['Bytespider']],
        [LlmCrawler::DUCKDUCKGO, ['DuckAssistBot']],
        [LlmCrawler::COHERE, ['cohere-ai']],
        [LlmCrawler::ALLEN_INSTITUTE, ['AI2Bot']],
    ];

    public function getLlmCrawler(Request $request): ?LlmCrawler
    {
        return $this->findByLlmUserAgent($request->headers->get('User-Agent'));
    }

    private function findByLlmUserAgent(?string $userAgent): ?LlmCrawler
    {
        if (null === $userAgent) {
            return null;
        }

        foreach (self::CRAWLER_USER_AGENTS as [$crawler, $crawlerUserAgents]) {
            foreach ($crawlerUserAgents as $crawlerUserAgent) {
                if (str_contains(strtoupper($userAgent), strtoupper($crawlerUserAgent))) {
                    return $crawler;
                }
            }
        }

        return null;
    }
}
