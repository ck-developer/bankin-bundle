<?php


namespace Bankin\Bundle\DependencyInjection\Compiler;


use Bankin\HttpClient\ClientConfigurator;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class HttplugPass implements CompilerPassInterface
{
    /**
     * You can modify the container here before it is dumped to PHP code.
     */
    public function process(ContainerBuilder $container)
    {
        if ($container->hasDefinition('httplug.client.app.http_methods.inner')) {
            $clientConfiguratorDefinition = $container->getDefinition(ClientConfigurator::class);
            $clientConfiguratorDefinition->addMethodCall('setHttpClient', [new Reference('httplug.client.app.http_methods.inner')]);
        }
    }
}