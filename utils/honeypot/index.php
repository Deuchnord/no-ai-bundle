<?php

declare(strict_types=1);

/*
 * This file is part of the Deuchnord\NoAiBundle bundle.
 *
 * (c) Jérôme Deuchnord <jerome@deuchnord.fr>
 *
 * Licensed under the EUPL-1.2-or-later
 */

use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;
use Symfony\Component\Routing\Attribute\Route;

require_once dirname(__FILE__).'/vendor/autoload_runtime.php';

class Kernel extends BaseKernel
{
    use MicroKernelTrait;

    public function registerBundles(): array
    {
        return [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Deuchnord\NoAiBundle\NoAiBundle(),
        ];
    }

    protected function configureContainer(ContainerConfigurator $container): void
    {
        $container->extension('framework', [
            'secret' => 'S3CRET',
            'trusted_proxies' => ['127.0.0.1', 'REMOTE_ADDR'],
        ]);
    }

    #[Route('/')]
    public function index(Request $request, LoggerInterface $logger): Response
    {
        $logger->info('Request from {ip_address}; User-Agent: {user_agent}', [
            'ip_address' => $request->getClientIp(),
            'user_agent' => $request->headers->get('User-Agent'),
        ]);

        return new Response('Hello World!');
    }

    #[Route('/robots.txt')]
    public function robots(Request $request, LoggerInterface $logger): Response
    {
        $logger->info('Request for "robots.txt" from {ip_address}', [
            'ip_address' => $request->getClientIp(),
        ]);

        return new Response('');
    }
}

return static function (array $context) {
    return new Kernel($context['APP_ENV'], (bool) $context['APP_DEBUG']);
};
