<?php

declare(strict_types=1);

namespace App\Auth\Classes\Email;

use App\_Core\Controller\EmailBuilder;
use App\_Core\Entity\EmailType;
use App\Auth\Entity\User;
use App\Auth\Entity\VerificationToken;
use Doctrine\ORM\EntityManagerInterface;
use Random\RandomException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

readonly class EmailVerificationService
{
    public function __construct(
        private EntityManagerInterface $em,
        private MailerInterface $mailer,
        private UrlGeneratorInterface $urlGenerator,
        private EmailBuilder $emailBuilder
    ) {}

    /**
     * @throws \Exception|TransportExceptionInterface|RandomException
     */
    public function sendVerificationEmail(User $user): void
    {
        $token = VerificationToken::create($user);
        $this->em->persist($token);
        $this->em->flush();

        $this->mailer->send($this->emailBuilder->getEmail(
            EmailType::VERIFY_EMAIL_ADDRESS,
            $user->getEmail(),
            [
                'verificationUrl' => $this->urlGenerator->generate(
                    'auth_verify_email',
                    ['token' => $token->getToken()],
                    UrlGeneratorInterface::ABSOLUTE_URL
                ),
            ]
        ));
    }

    public function verifyToken(string $token): ?User
    {
        $tokenEntity = $this->em->getRepository(VerificationToken::class)->findOneBy(['token' => $token]);

        if (!$tokenEntity || $tokenEntity->isExpired()) {
            return null;
        }

        $user = $tokenEntity->getUser();
        $user->verify();

        $this->em->remove($tokenEntity);
        $this->em->flush();

        return $user;
    }
}
