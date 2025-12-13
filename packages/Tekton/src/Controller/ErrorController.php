<?php

namespace Fortizan\Tekton\Controller;

use Symfony\Component\ErrorHandler\Exception\FlattenException;

class ErrorController
{
    public function __invoke(FlattenException $exception):void
    {
        echo('custom error controller worked :' . " " . $exception->getMessage());
    }
}