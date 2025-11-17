<?php

declare(strict_types=1);

namespace App\Auth\Controller;

use App\Application\Controller\AbstractApplicationController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractApplicationController
{
    #[Route('/auth/login', name: 'auth_login')]
    public function login(AuthenticationUtils $authUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_index');
        }

        if (($error = $authUtils->getLastAuthenticationError()) instanceof AuthenticationException) {
            $this->addFlash('error', $error->getMessageKey());
        }

        return $this->renderTemplate('auth/login.latte', [
            'lastUsername' => $authUtils->getLastUsername(),
            'title' => 'Login',
        ]);
    }

    #[Route('/auth/logout', name: 'auth_logout')]
    public function logout(): void
    {
        throw new \LogicException('Logout is handled by the firewall.');
    }
}
