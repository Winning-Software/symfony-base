<?php

declare(strict_types=1);

namespace App\Auth\Controller;

use App\Application\Controller\AbstractApplicationController;
use App\Auth\Classes\Email\EmailVerificationService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VerificationController extends AbstractApplicationController
{
    #[Route('/auth/verify', name: 'auth_verify_email')]
    public function verify(Request $request, EmailVerificationService $service): Response
    {
        $token = $request->query->get('token');

        if (!$token) {
            $this->addFlash('error', 'Invalid verification link.');
            return $this->redirectToRoute('auth_login');
        }

        $user = $service->verifyToken($token);

        if (!$user) {
            $this->addFlash('error', 'Verification link is invalid or expired.');
            return $this->redirectToRoute('auth_login');
        }

        $this->addFlash('success', 'Your account is now verified. You can log in.');
        return $this->redirectToRoute('auth_login');
    }
}
