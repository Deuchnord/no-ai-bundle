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
    public static function crawlerProvider(): iterable
    {
        foreach (LlmDetection::CRAWLER_USER_AGENTS as [$crawler, $crawlerUserAgents]) {
            foreach ($crawlerUserAgents as $crawlerUserAgent) {
                yield [$crawler, $crawlerUserAgent];
            }
        }
    }

    #[DataProvider('crawlerProvider')]
    public function testItDetectsLlmCrawlers(LlmCrawler $crawler, string $userAgent): void
    {
        $llmDetection = new LlmDetection();
        self::assertSame($crawler, $llmDetection->getLlmCrawler(Request::create('/', server: ['HTTP_USER_AGENT' => "Mozilla/5.0 AppleWebKit/537.36 (KHTML, like Gecko); compatible; $userAgent/1.1; +https://example.com"])));
    }

    public function testItDoesNotDetectRegularClients(): void
    {
        $llmDetection = new LlmDetection();
        self::assertNull($llmDetection->getLlmCrawler(Request::create('/', server: ['HTTP_USER_AGENT' => 'Mozilla/5.0 (X11; Linux x86_64; rv:141.0) Gecko/20100101 Firefox/141.0'])));
    }
}
