<?php

declare(strict_types=1);

/*
 * This file is part of the Deuchnord\NoAiBundle bundle.
 *
 * (c) Jérôme Deuchnord <jerome@deuchnord.fr>
 *
 * Licensed under the EUPL-1.2-or-later
 */

namespace Deuchnord\NoAiBundle\Test;

use Deuchnord\NoAiBundle\LlmCrawler;
use Deuchnord\NoAiBundle\LlmDetection;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;

class LlmDetectionTest extends TestCase
{
    /* @phpstan-ignore missingType.iterableValue */
    public static function crawlerProviderUserAgents(): iterable
    {
        foreach (LlmDetection::CRAWLER_USER_AGENTS as [$crawler, $crawlerUserAgents]) {
            foreach ($crawlerUserAgents as $crawlerUserAgent) {
                yield [$crawler, $crawlerUserAgent];
            }
        }
    }

    /**
     * @return list<array{LlmCrawler, list<string>}>
     */
    public static function crawlerProviderIpAddress(): array
    {
        return [
            [LlmCrawler::DIFFBOT, ['64.62.212.42', '64.62.255.255', '64.71.132.1']],
        ];
    }

    #[DataProvider('crawlerProviderUserAgents')]
    public function testItDetectsLlmCrawlersByUserAgent(LlmCrawler $crawler, string $userAgent): void
    {
        $llmDetection = new LlmDetection();
        $request = Request::create('/', server: ['HTTP_USER_AGENT' => "Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko); compatible; $userAgent/1.1; +https://example.com"]);
        self::assertSame($crawler, $llmDetection->getLlmCrawler($request));
    }

    /**
     * @param list<string> $ipAddresses
     */
    #[DataProvider('crawlerProviderIpAddress')]
    public function testItDetectsLlmCrawlersByIpAddress(LlmCrawler $crawler, array $ipAddresses): void
    {
        $llmDetection = new LlmDetection();
        foreach ($ipAddresses as $ipAddress) {
            $request = Request::create('/', server: ['REMOTE_ADDR' => $ipAddress]);
            self::assertSame($crawler, $llmDetection->getLlmCrawler($request));
        }
    }

    public function testItDoesNotDetectRegularClients(): void
    {
        $llmDetection = new LlmDetection();

        $ipAddresses = [
            '127.0.0.1',
            '203.0.113.5', // RFC 5737 section 3 compliant
        ];

        foreach ($ipAddresses as $ipAddress) {
            self::assertNull($llmDetection->getLlmCrawler(Request::create('/', server: [
                'HTTP_USER_AGENT' => null,
                'REMOTE_ADDR' => $ipAddress,
            ])));

            self::assertNull($llmDetection->getLlmCrawler(Request::create('/', server: [
                'HTTP_USER_AGENT' => 'Mozilla/5.0 (X11; Linux x86_64; rv:141.0) Gecko/20100101 Firefox/141.0',
                'REMOTE_ADDR' => $ipAddress,
            ])));
        }
    }
}
