<?php
namespace Mayordomo;

use Exception;
use Mayordomo\Infrastructure\Routing\Router;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\ResourceNotFoundException;

class Kernel
{
    /**
     * @var ContainerInterface
     */
    protected $container;

    /**
     * @internal
     * @required
     */
    public function __construct(ContainerInterface $container)
    {
        $previous = $this->container;
        $this->container = $container;

        return $previous;
    }

    public function start()
    {
        $router = $this->container->get(Router::class);

        try {
            $response = $router->handle();
        } catch (ResourceNotFoundException $exception) {
            $response = new Response('Not Found', 404);
        } catch (Exception $exception) {
            $response = new Response('An error occurred', 500);
        }

        echo $response->getContent();
    }
}