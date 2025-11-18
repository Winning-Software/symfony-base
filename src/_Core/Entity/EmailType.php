<?php

declare(strict_types=1);

namespace App\_Core\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table(name: 'ublEmailType', schema: 'Core')]
class EmailType
{
    const string PASSWORD_RESET = 'PASSWORD_RESET';
    const string VERIFY_EMAIL_ADDRESS = 'VERIFY_EMAIL_ADDRESS';

    #[Id]
    #[GeneratedValue]
    #[Column(name: 'intEmailTypeId', type: 'integer', options: ['unsigned' => true])]
    protected ?int $id = null;

    #[Column(name: 'strEmailTypeSubject', type: 'string', length: 255)]
    protected string $subject;

    #[Column(name: 'strEmailTypeHandle', type: 'string', length: 100)]
    protected string $handle;

    #[Column(name: 'strTemplate', type: 'string')]
    protected string $template;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSubject(): string
    {
        return $this->subject;
    }

    public function getHandle(): string
    {
        return $this->handle;
    }

    public function getTemplate(): string
    {
        return $this->template;
    }
}
