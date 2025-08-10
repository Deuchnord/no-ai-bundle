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

interface LlmDetectionInterface
{
    /**
     * Analyze the request and return the LLM crawler that made it if it is one of the known ones.
     * If it is not, return null.
     */
    public function getLlmCrawler(Request $request): ?LlmCrawler;
}
