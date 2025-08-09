<?php

declare(strict_types=1);

use PhpCsFixer\Config;
use PhpCsFixer\Finder;
use PhpCsFixer\Runner\Parallel\ParallelConfigFactory;

$finder = Finder::create()
    ->in(__DIR__)
;

return (new Config())
    ->setFinder($finder)
    ->setParallelConfig(ParallelConfigFactory::detect())
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => true,
        'declare_strict_types' => true,
        'header_comment' => [
            'header' => <<<'EOF'
                This file is part of the Deuchnord\NoAiBundle bundle.
                
                (c) Jérôme Deuchnord <jerome@deuchnord.fr>
                                
                Licensed under the EUPL-1.2-or-later
                EOF,
        ]
    ])
;