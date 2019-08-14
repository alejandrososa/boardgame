<?php

namespace Mayordomo\Infrastructure\Engine;

use Symfony\Component\Templating\Loader\FilesystemLoader;
use Symfony\Component\Templating\PhpEngine;
use Symfony\Component\Templating\TemplateNameParser;

class Templating
{
    /**
     * @var PhpEngine
     */
    private $templating;

    public function __construct()
    {
        $filesystemLoader = new FilesystemLoader(ROOT_PATH.'/src/Ui/Templates/%name%');
        $this->templating = new PhpEngine(new TemplateNameParser(), $filesystemLoader);
    }

    public function render(string $view, array $parameters = []): string
    {
        return $this->templating->render($view, $parameters);
    }
}