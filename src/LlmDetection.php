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

    public const CRAWLER_IP_ADDRESS_RANGES = [
        [LlmCrawler::DIFFBOT, [
            ['64.62.128.0', '64.62.255.255'],
            ['64.71.128.0', '64.72.0.0'],
        ]],
    ];

    public function getLlmCrawler(Request $request): ?LlmCrawler
    {
        return $this->findByLlmUserAgent($request->headers->get('User-Agent'), $request->getClientIp());
    }

    private function findByLlmUserAgent(?string $userAgent, ?string $ipAddress): ?LlmCrawler
    {
        if (null === $userAgent) {
            return null;
        }

        if ($crawler = $this->findCrawlerByUserAgent($userAgent)) {
            return $crawler;
        }

        return $ipAddress ? $this->findCrawlerByIpAddress($ipAddress) : null;
    }

    private function findCrawlerByUserAgent(string $userAgent): ?LlmCrawler
    {
        foreach (self::CRAWLER_USER_AGENTS as [$crawler, $crawlerUserAgents]) {
            foreach ($crawlerUserAgents as $crawlerUserAgent) {
                if (str_contains(strtoupper($userAgent), strtoupper($crawlerUserAgent))) {
                    return $crawler;
                }
            }
        }

        return null;
    }

    private function findCrawlerByIpAddress(string $ipAddress): ?LlmCrawler
    {
        foreach (self::CRAWLER_IP_ADDRESS_RANGES as [$crawler, $crawlerIpRanges]) {
            foreach ($crawlerIpRanges as [$crawlerIpRangeStart, $crawlerIpRangeEnd]) {
                if (ip2long($ipAddress) >= ip2long($crawlerIpRangeStart) && ip2long($ipAddress) <= ip2long($crawlerIpRangeEnd)) {
                    return $crawler;
                }
            }
        }

        return null;
    }
}
