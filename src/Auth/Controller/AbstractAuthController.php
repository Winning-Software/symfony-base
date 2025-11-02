<?php

declare(strict_types=1);

namespace App\Auth\Controller;

use CloudBase\LatteHelper\Controller\AbstractLatteController;

class AbstractAuthController extends AbstractLatteController
{
    protected string $templateDir = '/templates/auth';
}
