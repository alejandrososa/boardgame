<?php

namespace Mayordomo\Ui\Controller;

use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class BaseController implements ContainerAwareInterface
{
    use ControllerTrait;

    public function __construct(?ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * @var ContainerInterface
     */
    protected $container;

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }
}