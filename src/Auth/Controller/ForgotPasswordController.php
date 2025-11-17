<?php

declare(strict_types=1);

namespace App\Auth\Controller;

use App\Application\Controller\AbstractApplicationController;
use App\Auth\Classes\Email\PasswordResetService;
use App\Auth\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ForgotPasswordController extends AbstractApplicationController
{
    #[Route('/auth/password-reset/request', name: 'auth_forgot_password')]
    public function requestNewPassword(Request $request, EntityManagerInterface $em, PasswordResetService $service): Response
    {
        if ($request->isMethod('POST')) {
            $email = $request->request->get('email');
            $user = $em->getRepository(User::class)->findOneBy(['email' => $email]);

            if ($user instanceof User) {
                $service->sendResetEmail($user);
            }

            $this->addFlash('success', 'If your email exists, a reset link has been sent.');
            return $this->redirectToRoute('auth_login');
        }

        return $this->renderTemplate('auth/forgot-password.latte', [
            'title' => 'Request Password Reset',
        ]);
    }

    #[Route('/auth/reset-password', name: 'auth_reset_password')]
    public function reset(Request $request, PasswordResetService $service, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $em): Response
    {
        $token = $request->query->get('token');

        if (!$token) {
            $this->addFlash('error', 'Invalid reset link.');
            return $this->redirectToRoute('auth_login');
        }

        $user = $service->validateToken($token);

        if (!$user) {
            $this->addFlash('error', 'Reset link is invalid or expired.');
            return $this->redirectToRoute('auth_login');
        }

        if ($request->isMethod('POST')) {
            $password = $request->request->get('password');
            $confirmPassword = $request->request->get('confirm_password');

            if ($password !== $confirmPassword) {
                $this->addFlash('error', 'Passwords do not match.');
            } else {
                $user->setPassword($passwordHasher->hashPassword($user, $password));
                $em->flush();
                $service->consumeToken($token);
                $this->addFlash('success', 'Password successfully reset. You can now log in.');
                return $this->redirectToRoute('auth_login');
            }
        }

        return $this->renderTemplate('auth/reset_password.latte', ['token' => $token]);
    }
}
