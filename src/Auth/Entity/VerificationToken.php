<?php

declare(strict_types=1);

namespace App\Auth\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Application\Entity\AbstractBaseEntity;
use Random\RandomException;

#[ORM\Entity]
#[ORM\Table(name: 'tblVerificationToken', schema: 'Auth')]
class VerificationToken extends AbstractBaseEntity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'intVerificationTokenId', type: 'integer', options: ['unsigned' => true])]
    protected ?int $id = null;

    #[ORM\Column(name: 'strToken', type: 'string', length: 100, unique: true)]
    private string $token;

    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'intUserId', referencedColumnName: 'intUserId', nullable: false)]
    private User $user;

    #[ORM\Column(name: 'dtmExpires', type: 'datetime')]
    private \DateTimeInterface $expiresAt;

    /**
     * @throws RandomException
     */
    public static function create(User $user): self
    {
        $self = new self();

        $self->user = $user;
        $self->token = bin2hex(random_bytes(32));
        $self->expiresAt = (new \DateTime('+1 day'));

        return $self;
    }

    public function isExpired(): bool
    {
        return new \DateTime() > $this->expiresAt;
    }

    public function getToken(): string
    {
        return $this->token;
    }

    public function getUser(): User
    {
        return $this->user;
    }
}
