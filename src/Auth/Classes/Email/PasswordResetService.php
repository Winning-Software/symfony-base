<?php

declare(strict_types=1);

namespace App\Auth\Classes\Email;

use App\Auth\Entity\User;
use App\Auth\Entity\PasswordResetToken;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

readonly class PasswordResetService
{
    public function __construct(
        private EntityManagerInterface $em,
        private MailerInterface $mailer,
        private UrlGeneratorInterface $urlGenerator
    ) {}

    public function sendResetEmail(User $user): void
    {
        $token = new PasswordResetToken($user);
        $this->em->persist($token);
        $this->em->flush();

        $resetUrl = $this->urlGenerator->generate(
            'auth_reset_password',
            ['token' => $token->getToken()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $email = (new Email())
            ->from('no-reply@example.com')
            ->to($user->getEmail())
            ->subject('Reset Your Password')
            ->html("
                <p>Click the link below to reset your password:</p>
                <p><a href='{$resetUrl}'>Reset Password</a></p>
            ");

        $this->mailer->send($email);
    }

    public function validateToken(string $token): ?User
    {
        $repo = $this->em->getRepository(PasswordResetToken::class);
        $tokenEntity = $repo->findOneBy(['token' => $token]);

        if (!$tokenEntity || $tokenEntity->isExpired()) {
            return null;
        }

        return $tokenEntity->getUser();
    }

    public function consumeToken(string $token): void
    {
        $repo = $this->em->getRepository(PasswordResetToken::class);
        $tokenEntity = $repo->findOneBy(['token' => $token]);

        if ($tokenEntity) {
            $this->em->remove($tokenEntity);
            $this->em->flush();
        }
    }
}
