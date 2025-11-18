<?php

declare(strict_types=1);

namespace App\Auth\Controller;

use App\Application\Controller\AbstractApplicationController;
use App\Auth\Form\LoginForm;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractApplicationController
{
    #[Route('/auth/login', name: 'auth_login')]
    public function login(): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_index');
        }

        return $this->renderTemplate('auth/login.latte', [
            'form' => $this->createForm(LoginForm::class)->createView(),
            'title' => 'Login',
        ]);
    }

    #[Route('/auth/logout', name: 'auth_logout')]
    public function logout(): void
    {
        throw new \LogicException('Logout is handled by the firewall.');
    }
}
