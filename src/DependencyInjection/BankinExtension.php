<?php

namespace Bankin\Bundle\DependencyInjection;

use Bankin\Bankin;
use Bankin\HttpClient\ClientConfigurator;
use Bankin\Hydrator\ModelHydrator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Reference;

class BankinExtension extends Extension implements PrependExtensionInterface
{
    /**
     * Loads a specific configuration.
     *
     * @throws \InvalidArgumentException When provided tag is not defined in this extension
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $modelHydratorDefinition = new Definition(ModelHydrator::class);

        $container->setDefinition(ModelHydrator::class, $modelHydratorDefinition);
        $container->setAlias('bankin.hydrator.model', ModelHydrator::class);

        $clientConfiguratorDefinition = new Definition(ClientConfigurator::class);
        $clientConfiguratorDefinition->addMethodCall('setClientId', [$config['client_id']]);
        $clientConfiguratorDefinition->addMethodCall('setClientSecret', [$config['client_secret']]);

        $container->setDefinition(ClientConfigurator::class, $clientConfiguratorDefinition);
        $container->setAlias('bankin.client_configurator', ClientConfigurator::class);

        $bankinDefinition = new Definition(
            Bankin::class,
            [
                new Reference(ClientConfigurator::class),
                null,
                null,
                new Reference(ModelHydrator::class)
            ]
        );

        $container->setDefinition(Bankin::class, $bankinDefinition);
        $container->setAlias('bankin', Bankin::class);
    }

    /**
     * Allow an extension to prepend the extension configurations.
     */
    public function prepend(ContainerBuilder $container)
    {
        $bundles = $container->getParameter('kernel.bundles');

        if (!array_key_exists('HttplugBundle', $bundles)) {
            return;
        }

        $container->prependExtensionConfig('httplug', [
            'clients' => [
                'bankin' => [
                    'factory' => 'httplug.factory.guzzle6',
                    'http_methods_client' => true,
                    'plugins' => [
                        [
                            'cache' => [
                                'cache_pool' => 'bankin.cache'
                            ],
                        ]
                    ]
                ]
            ]
        ]);
    }
}