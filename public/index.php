<?php
use Mayordomo\Kernel;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader as ContainerFileLoader;

define('ROOT_PATH', dirname(dirname(__FILE__)));
require ROOT_PATH.'/vendor/autoload.php';

//dependency injection
$container = new ContainerBuilder();
$containerLoader = new ContainerFileLoader($container, new FileLocator(__DIR__));
$containerLoader->load(ROOT_PATH.'/src/Infrastructure/Symfony/config/services.yaml');
$container->setParameter('container.autowiring.strict_mode', true);
$container->setParameter('container.dumper.inline_class_loader', true);
$container->compile();

//app
$mayordomo = new Kernel($container);
$mayordomo->start();
