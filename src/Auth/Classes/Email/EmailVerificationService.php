<?php

declare(strict_types=1);

namespace App\Auth\Classes\Email;

use App\Auth\Entity\User;
use App\Auth\Entity\VerificationToken;
use Doctrine\ORM\EntityManagerInterface;
use Random\RandomException;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

readonly class EmailVerificationService
{
    public function __construct(
        private EntityManagerInterface $em,
        private MailerInterface $mailer,
        private UrlGeneratorInterface $urlGenerator
    ) {}

    /**
     * @throws TransportExceptionInterface|RandomException
     */
    public function sendVerificationEmail(User $user): void
    {
        $token = VerificationToken::create($user);
        $this->em->persist($token);
        $this->em->flush();

        $verificationUrl = $this->urlGenerator->generate(
            'auth_verify_email',
            ['token' => $token->getToken()],
            UrlGeneratorInterface::ABSOLUTE_URL
        );

        $email = (new Email())
            ->from('no-reply@example.com')
            ->to($user->getEmail())
            ->subject('Verify your account')
            ->html("
                <p>Click the link below to verify your account:</p>
                <p><a href='{$verificationUrl}'>Verify Account</a></p>
            ");

        $this->mailer->send($email);
    }

    public function verifyToken(string $token): ?User
    {
        $repo = $this->em->getRepository(VerificationToken::class);
        $tokenEntity = $repo->findOneBy(['token' => $token]);

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
