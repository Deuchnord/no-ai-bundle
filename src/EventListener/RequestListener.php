<?php

declare(strict_types=1);

/*
 * This file is part of the Deuchnord\NoAiBundle bundle.
 *
 * (c) Jérôme Deuchnord <jerome@deuchnord.fr>
 *
 * Licensed under the EUPL-1.2-or-later
 */

namespace Deuchnord\NoAiBundle\EventListener;

use Deuchnord\NoAiBundle\Event\LlmCrawlerBlockedEvent;
use Deuchnord\NoAiBundle\LlmDetection;
use Psr\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\Attribute\AsEventListener;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\RequestEvent;
use Symfony\Component\HttpKernel\KernelEvents;

/**
 * This event listener checks if the request comes from an LLM crawler client.
 * If it does, return an appropriate response.
 */
#[AsEventListener(event: KernelEvents::REQUEST, method: '__invoke', priority: 4096)]
final readonly class RequestListener
{
    public function __construct(
        private LlmDetection $llmDetection,
        private EventDispatcherInterface $dispatcher,
    ) {
    }

    public function __invoke(RequestEvent $event): void
    {
        if (!$event->isMainRequest()) {
            return;
        }

        if (!$crawler = $this->llmDetection->getLlmCrawler($event->getRequest())) {
            return;
        }

        $event->setResponse(new Response('', Response::HTTP_FORBIDDEN));
        $this->dispatcher->dispatch(new LlmCrawlerBlockedEvent($crawler));
    }
}
