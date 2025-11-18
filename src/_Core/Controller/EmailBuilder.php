<?php

declare(strict_types=1);

namespace App\_Core\Controller;

use App\_Core\Entity\EmailType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class EmailBuilder extends AbstractApplicationController
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    /**
     * @param string $emailTypeHandle
     * @param string $to
     * @param array<string, mixed> $templateOptions
     *
     * @throws \Exception
     *
     * @return Email
     */
    public function getEmail(string $emailTypeHandle, string $to, array $templateOptions = []): Email
    {
        $emailType = $this->entityManager->getRepository(EmailType::class)->findOneBy(['handle' => $emailTypeHandle]);

        if (!$emailType) {
            throw new \Exception('Email type not found');
        }

        $mailFromAddress = $_ENV['MAIL_FROM_ADDRESS'];
        $mailFromName = $_ENV['MAIL_FROM_NAME'];

        if (!is_string($mailFromAddress) || !is_string($mailFromName)) {
            throw new \Exception('Mail from address or name is not set');
        }

        $emailContent = $this->renderTemplate($emailType->getTemplate(), $templateOptions)->getContent();

        if (!is_string($emailContent)) {
            throw new \Exception('Email template is not valid');
        }

        return (new Email())
            ->from(new Address($mailFromAddress, $mailFromName))
            ->to($to)
            ->subject($emailType->getSubject())
            ->html($emailContent);
    }
}