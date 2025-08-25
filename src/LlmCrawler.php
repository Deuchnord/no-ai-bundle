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

enum LlmCrawler
{
    case OPEN_AI;
    case ANTHROPIC;
    case PERPLEXITY;
    case GOOGLE;

    /** @deprecated Microsoft uses Diffbot's crawlers. Use {@see self::DIFFBOT} instead. */
    case MICROSOFT;

    case AMAZON;
    case APPLE;
    case BYTEDANCE;
    case DUCKDUCKGO;
    case COHERE;
    case ALLEN_INSTITUTE;
    case DIFFBOT;
}
