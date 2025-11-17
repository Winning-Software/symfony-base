<?php

declare(strict_types=1);

namespace App\Auth\Controller;

use App\Application\Controller\AbstractApplicationController;
use App\Auth\Classes\DTO\RegistrationDTO;
use App\Auth\Classes\Email\EmailVerificationService;
use App\Auth\Entity\User;
use App\Auth\Form\RegistrationForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractApplicationController
{
    #[Route('/auth/register', name: 'auth_register')]
    public function register(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher,
        EmailVerificationService $verificationService
    ): Response {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_index');
        }

        $data = new RegistrationDTO();
        $form = $this->createForm(RegistrationForm::class, $data);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            try {
                if (!$form->isValid()) {
                    foreach ($form->getErrors(true) as $currentError) {
                        if ($currentError instanceof FormError) {
                            throw new \Exception($currentError->getMessage());
                        }
                    }
                }

                if (!$data->passwordsMatch()) {
                    throw new \Exception('Passwords do not match');
                }

                $existingUser = $em->getRepository(User::class)->findOneBy(['email' => $data->getEmail()]);

                if ($existingUser instanceof User) {
                    throw new \Exception('Email is already in use');
                }

                $user = User::create($data, $passwordHasher);

                $em->persist($user);
                $em->flush();

                $verificationService->sendVerificationEmail($user);

                $this->addFlash('success', 'Account created. You can now log in.');

                return $this->redirectToRoute('auth_login');
            } catch (\Exception $e) {
                $this->addFlash('error', $e->getMessage());
            }
        }

        return $this->renderTemplate('auth/register.latte', [
            'data' => $data,
            'form' => $form->createView(),
            'title' => 'Register',
        ]);
    }
}
