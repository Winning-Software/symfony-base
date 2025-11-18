<?php

declare(strict_types=1);

namespace App\Auth\Classes\Email;

use App\_Core\Controller\EmailBuilder;
use App\_Core\Entity\EmailType;
use App\Auth\Entity\PasswordResetToken;
use App\Auth\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

readonly class PasswordResetService
{
    public function __construct(
        private EntityManagerInterface $em,
        private MailerInterface $mailer,
        private UrlGeneratorInterface $urlGenerator,
        private EmailBuilder $emailBuilder
    ) {
    }

    /**
     * @throws \Exception|TransportExceptionInterface
     */
    public function sendResetEmail(User $user): void
    {
        $token = new PasswordResetToken($user);
        $this->em->persist($token);
        $this->em->flush();

        $this->mailer->send($this->emailBuilder->getEmail(
            EmailType::PASSWORD_RESET,
            $user->getEmail(),
            [
                'resetUrl' => $this->urlGenerator->generate(
                    'auth_reset_password',
                    ['token' => $token->getToken()],
                    UrlGeneratorInterface::ABSOLUTE_URL
                ),
            ]
        ));
    }

    public function validateToken(string $token): ?User
    {
        $tokenEntity = $this->em->getRepository(PasswordResetToken::class)->findOneBy(['token' => $token]);

        if (!$tokenEntity || $tokenEntity->isExpired()) {
            return null;
        }

        return $tokenEntity->getUser();
    }

    public function consumeToken(string $token): void
    {
        $tokenEntity = $this->em->getRepository(PasswordResetToken::class)->findOneBy(['token' => $token]);

        if ($tokenEntity) {
            $this->em->remove($tokenEntity);
            $this->em->flush();
        }
    }
}
