<?php

declare(strict_types=1);

namespace App\Auth\Entity;

use App\_Core\Entity\AbstractBaseEntity;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity]
#[ORM\Table(name: 'tblPasswordResetToken', schema: 'Auth')]
#[ORM\Index(name: 'I_tblPasswordResetToken_intUserId', columns: ['intUserId'])]
class PasswordResetToken extends AbstractBaseEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'intPasswordResetTokenId', type: 'integer', options: ['unsigned' => true])]
    protected ?int $id = null;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'intUserId', referencedColumnName: 'intUserId', nullable: false)]
    private User $user;

    #[ORM\Column(name: 'strToken', type: 'string', length: 100, unique: true)]
    private string $token;

    #[ORM\Column(name: 'dtmExpires', type: 'datetime')]
    private \DateTimeInterface $expiresAt;

    public function __construct(User $user)
    {
        $this->user = $user;
        $this->token = bin2hex(random_bytes(32));
        $this->expiresAt = new \DateTime('+1 hour');
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): User
    {
        return $this->user;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getExpiresAt(): \DateTimeInterface
    {
        return $this->expiresAt;
    }

    public function isExpired(): bool
    {
        return new \DateTime() > $this->expiresAt;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }
}
