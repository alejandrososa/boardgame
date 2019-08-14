<?php

namespace Mayordomo\Infrastructure\Routing;

use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentResolver;
use Symfony\Component\HttpKernel\Controller\ControllerResolver;
use Symfony\Component\Routing\Loader\YamlFileLoader as RoutingFileLoader;
use Symfony\Component\Routing\Matcher\UrlMatcher;
use Symfony\Component\Routing\RequestContext;

class Router
{
    /**
     * @var \Symfony\Component\Routing\RouteCollection
     */
    private $routes;

    /**
     * @var RequestContext
     */
    private $context;
    private $request;
    /**
     * @var ControllerResolver
     */
    private $controllerResolver;
    /**
     * @var ArgumentResolver
     */
    private $argumentResolver;

    public function __construct(?ContainerInterface $container = null)
    {
        $this->container = $container;
        $routingLoader = new RoutingFileLoader(new FileLocator(__DIR__));
        $this->routes = $routingLoader->load(ROOT_PATH.'/src/Infrastructure/Symfony/config/routes.yaml');
        $this->request = Request::createFromGlobals();
        $this->context = new RequestContext();
        $this->controllerResolver = new ControllerResolver();
        $this->argumentResolver = new ArgumentResolver();

        $this->request->headers->set('Cache-Control', 'no-cache');
    }

    public function handle()
    {
        $this->context->fromRequest($this->request);
        $matcher = new UrlMatcher($this->routes, $this->context);
        $this->request->attributes->add($matcher->match($this->request->getPathInfo()));

        $controller = $this->getController();
        $arguments = $this->getArguments();

        $controllerObject = $controller;
        $controllerName = array_shift($controllerObject);
        if(!empty($controllerName) && is_object($controllerName)){
            $controllerClass = $this->container->get(get_class($controllerName));
            array_shift($controller);
            array_unshift($controller, $controllerClass);
        }
        return call_user_func_array($controller, $arguments);
    }

    private function getController()
    {
        return $this->controllerResolver->getController($this->request);
    }

    private function getArguments(): ?array
    {
        return $this->argumentResolver->getArguments($this->request, $this->getController());
    }
}