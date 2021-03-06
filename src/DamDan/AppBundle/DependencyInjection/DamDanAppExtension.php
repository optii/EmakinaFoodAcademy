<?php

namespace DamDan\AppBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;

/**
 * This is the class that loads and manages your bundle configuration.
 *
 * @link http://symfony.com/doc/current/cookbook/bundles/extension.html
 */
class DamDanAppExtension extends Extension
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new Loader\YamlFileLoader($container, new FileLocator(__DIR__.'/../Resources/config'));
        $loader->load('services.yml');

        if (!$config['administrator_email']) {
            throw new \InvalidArgumentException('The "administrator_email" option must be set');
        }

        if(!$config['upload_dir']){
            throw new \InvalidArgumentException('The "upload_dir" option must be set');
        }

        $container->setParameter('dandam_app.administrator.email', $config['administrator_email']);
        $container->setParameter('dandam_app.upload_dir', $config['upload_dir']);
    }
}
