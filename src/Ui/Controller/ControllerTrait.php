<?php

namespace Mayordomo\Ui\Controller;

use Mayordomo\Infrastructure\Engine\Templating;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Envelope;

/**
 * Common features needed in controllers.
 *
 * @author Fabien Potencier <fabien@symfony.com>
 *
 * @internal
 *
 * @property ContainerInterface $container
 */
trait ControllerTrait
{
    /**
     * Returns true if the service id is defined.
     *
     * @final
     */
    protected function has(string $id): bool
    {
        return $this->container->has($id);
    }

    /**
     * Gets a container service by its id.
     *
     * @return object The service
     *
     * @final
     */
    protected function get(string $id)
    {
        return $this->container->get($id);
    }

    /**
     * Returns a JsonResponse that uses the serializer component if enabled, or json_encode.
     *
     * @final
     */
    protected function json($data, int $status = 200, array $headers = []): JsonResponse
    {
        return new JsonResponse($data, $status, $headers);
    }
    /**
     * Returns a rendered view.
     *
     * @final
     */
    protected function render(string $view, array $parameters = [], Response $response = null): Response
    {
        if ($this->container->has(Templating::class)) {
            $content = $this->container->get(Templating::class)->render($view, $parameters);
        }

        if (null === $response) {
            $response = new Response();
        }

        $response->setContent($content);

        return $response;
    }

    /**
     * Renders a view.
     *
     * @final
     */
    protected function renderView(string $view, array $parameters = [], Response $response = null): Response
    {
        if ($this->container->has('templating')) {
            $content = $this->container->get('templating')->render($view, $parameters);
        } elseif ($this->container->has('twig')) {
            $content = $this->container->get('twig')->render($view, $parameters);
        } else {
            throw new \LogicException('You can not use the "render" method if the Templating Component or the Twig Bundle are not available. Try running "composer require symfony/twig-bundle".');
        }

        if (null === $response) {
            $response = new Response();
        }

        $response->setContent($content);

        return $response;
    }

    /**
     * Dispatches a message to the bus.
     *
     * @param object|Envelope $message The message or the message pre-wrapped in an envelope
     *
     * @final
     */
    protected function dispatchMessage($message): Envelope
    {
        if (!$this->container->has('message_bus')) {
            $message = class_exists(Envelope::class) ? 'You need to define the "messenger.default_bus" configuration option.' : 'Try running "composer require symfony/messenger".';
            throw new \LogicException('The message bus is not enabled in your application. '.$message);
        }

        return $this->container->get('message_bus')->dispatch($message);
    }
}
