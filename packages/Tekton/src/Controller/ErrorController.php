<?php

namespace Fortizan\Tekton\Controller;

use Symfony\Component\ErrorHandler\Exception\FlattenException;

class ErrorController
{
    public function handle(FlattenException $exception):void
    {
        echo('custom error controller worked :' . " " . $exception->getMessage());
    }
}