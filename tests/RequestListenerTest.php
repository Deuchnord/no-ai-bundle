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

use Deuchnord\NoAiBundle\Event\LlmCrawlerBlockedEvent;
use Deuchnord\NoAiBundle\EventListener\RequestListener;
use Deuchnord\NoAiBundle\LlmCrawler;
use Deuchnord\NoAiBundle\LlmDetectionInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

final class RequestListenerTest extends TestCase
{
    /** @return iterable<string, array{LlmCrawler}> */
    public static function crawlerProvider(): iterable
    {
        foreach (LlmCrawler::cases() as $crawler) {
            yield $crawler->name => [$crawler];
        }
    }

    #[DataProvider('crawlerProvider')]
    public function testItChangeTheResponseForLlmCrawlers(LlmCrawler $crawler): void
    {
        $requestListener = new RequestListener(
            $llmDetection = $this->createMock(LlmDetectionInterface::class),
            $eventDispatcher = $this->createMock(EventDispatcherInterface::class),
        );

        $event = $this->createMock(RequestEvent::class);
        $event->method('isMainRequest')->willReturn(true);
        $llmDetection->method('getLlmCrawler')->willReturn($crawler);

        $event->expects($this->once())->method('setResponse');
        $eventDispatcher->expects($this->once())->method('dispatch')->with(new LlmCrawlerBlockedEvent($crawler));

        $requestListener($event);
    }

    public function testItDoesNotChangeTheResponseForRegularUsers(): void
    {
        $requestListener = new RequestListener(
            $llmDetection = $this->createMock(LlmDetectionInterface::class),
            $eventDispatcher = $this->createMock(EventDispatcherInterface::class),
        );

        $event = $this->createMock(RequestEvent::class);
        $event->method('isMainRequest')->willReturn(true);
        $llmDetection->method('getLlmCrawler')->willReturn(null);

        $event->expects($this->never())->method('setResponse');
        $eventDispatcher->expects($this->never())->method('dispatch');

        $requestListener($event);
    }

    public function testItDoesNotChangeTheResponseForSubRequests(): void
    {
        $requestListener = new RequestListener(
            $llmDetection = $this->createMock(LlmDetectionInterface::class),
            $eventDispatcher = $this->createMock(EventDispatcherInterface::class),
        );

        $event = $this->createMock(RequestEvent::class);
        $event->method('isMainRequest')->willReturn(false);

        $llmDetection->expects($this->never())->method('getLlmCrawler');
        $event->expects($this->never())->method('setResponse');
        $eventDispatcher->expects($this->never())->method('dispatch');

        $requestListener($event);
    }
}
