<?php

declare(strict_types=1);

namespace App\Auth\Controller;

use App\_Core\Controller\AbstractApplicationController;
use App\Auth\Classes\DTO\PasswordResetDTO;
use App\Auth\Classes\DTO\RequestPasswordResetDTO;
use App\Auth\Classes\Email\PasswordResetService;
use App\Auth\Entity\PasswordResetToken;
use App\Auth\Entity\User;
use App\Auth\Form\PasswordResetForm;
use App\Auth\Form\RequestPasswordResetLinkForm;
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
        $data = new RequestPasswordResetDTO();
        $form = $this->createForm(RequestPasswordResetLinkForm::class, $data);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $em->getRepository(User::class)->findOneBy(['email' => $data->getEmail()]);

            if ($user instanceof User) {
                $existingTokens = $em->getRepository(PasswordResetToken::class)->findBy(['user' => $user]);

                if (count($existingTokens)) {
                    foreach ($existingTokens as $token) {
                        $em->remove($token);
                    }

                    $em->flush();
                }

                $service->sendResetEmail($user);
            }

            $this->addFlash('success', 'If your email exists, a reset link has been sent.');

            return $this->redirectToRoute('auth_login');
        }

        return $this->renderTemplate('_core/pages/auth/forgot-password', [
            'title' => 'Request Password Reset',
            'form' => $form->createView(),
        ]);
    }

    #[Route('/auth/reset-password', name: 'auth_reset_password')]
    public function reset(Request $request, PasswordResetService $passwordResetService, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $em): Response
    {
        $token = $request->query->get('token');

        if (!is_string($token)) {
            $this->addFlash('error', 'Invalid reset link.');

            return $this->redirectToRoute('auth_login');
        }

        $user = $passwordResetService->validateToken($token);

        if (!$user) {
            $this->addFlash('error', 'Reset link is invalid or expired.');

            return $this->redirectToRoute('auth_login');
        }

        $data = new PasswordResetDTO();
        $form = $this->createForm(PasswordResetForm::class, $data);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid() && $data->validate()) {
            $user->setPassword($passwordHasher->hashPassword($user, $data->getPassword()));
            $em->flush();
            $passwordResetService->consumeToken($token);
            $this->addFlash('success', 'Password successfully reset. You can now log in.');

            return $this->redirectToRoute('auth_login');
        }

        return $this->renderTemplate(
            '_core/pages/auth/password-reset',
            [
                'token' => $token,
                'title' => 'Reset Password',
                'form' => $form->createView(),
                'data' => $data,
            ]
        );
    }
}
