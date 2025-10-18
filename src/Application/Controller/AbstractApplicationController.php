<?php

declare(strict_types=1);

namespace App\Application\Controller;

use CloudBase\LatteHelper\AbstractLatteController;

class AbstractApplicationController extends AbstractLatteController
{
    protected string $templateDir = '/templates/application';
}
