<?php

declare(strict_types=1);

namespace App\Application\Controller;

use App\_Core\Controller\AbstractApplicationController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class IndexController extends AbstractApplicationController
{
    #[Route('/', name: 'app_index')]
    public function index(): Response
    {
        return $this->renderTemplate('application/index');
    }
}
