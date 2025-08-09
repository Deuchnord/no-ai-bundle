<?php

declare(strict_types=1);

/*
 * This file is part of the Deuchnord\NoAiBundle bundle.
 *
 * (c) Jérôme Deuchnord <jerome@deuchnord.fr>
 *
 * Licensed under the EUPL-1.2-or-later
 */

namespace Deuchnord\NoAiBundle\Event;

use Deuchnord\NoAiBundle\LlmCrawler;
use Symfony\Contracts\EventDispatcher\Event;

final class LlmCrawlerBlockedEvent extends Event
{
    public function __construct(public readonly LlmCrawler $crawler)
    {
    }
}
