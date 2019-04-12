<?php

namespace PLejeune\TableBundle\DependencyInjection;


use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Yaml\Yaml;

class PLejeuneTableExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container)
    {
        $loader = new YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yaml');

        $defaultConfig = Yaml::parse(
            file_get_contents(__DIR__.'/../Resources/config/config.yaml')
        );

        $configuration = new Configuration();
        $processedConfig = $this->processConfiguration($configuration, $configs);

        $bundleConfig = $this->arrayMergeRecursiveDistinct($processedConfig, $defaultConfig);

        $container->setParameter('plejeune.table.config', $bundleConfig);
    }

    public function prepend(ContainerBuilder $container)
    {
        if (!$container->hasExtension('twig')) {
            return;
        }

        $container->prependExtensionConfig('twig', array('paths' => array(__DIR__.'/../Resources/views' => "PLejeuneTable")));
    }

    private function arrayMergeRecursiveDistinct(array &$array1, array &$array2)
    {
        $merged = $array1;

        foreach ($array2 as $key => &$value) {
            if (is_array($value) && isset($merged[$key]) && is_array($merged[$key])) {
                $merged[$key] = $this->arrayMergeRecursiveDistinct($merged[$key], $value);
            }elseif(!isset($merged[$key])) {
                $merged[$key] = $value;
            }
        }

        return $merged;
    }
}
