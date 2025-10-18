<?php

declare(strict_types=1);

namespace App\Application\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class IndexController extends AbstractApplicationController
{
    #[Route('/')]
    public function index(): Response
    {
        return $this->renderTemplate('index');
    }
}
