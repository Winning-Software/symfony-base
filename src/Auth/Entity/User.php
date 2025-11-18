<?php

declare(strict_types=1);

namespace App\Auth\Entity;

use App\_Core\Entity\AbstractBaseEntity;
use App\Auth\Classes\DTO\RegistrationDTO;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity]
#[ORM\Table(name: 'tblUser', schema: 'Auth')]
class User extends AbstractBaseEntity implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'intUserId', type: 'integer', options: ['unsigned' => true])]
    protected ?int $id = null;

    #[ORM\Column(name: 'strEmail', length: 180, unique: true, nullable: false)]
    private string $email;

    #[ORM\Column(name: 'strPassword', length: 255, nullable: false, options: ['comment' => 'Hashed password'])]
    private string $password;

    #[ORM\Column(name: 'bolActive', type: 'boolean', nullable: false)]
    private bool $isActive;

    #[ORM\Column(name: 'bolVerified', type: 'boolean', nullable: false)]
    private bool $isVerified;

    /**
     * @var string[]
     */
    private array $roles = [];

    public static function create(RegistrationDTO $dto, UserPasswordHasherInterface $passwordHasher): self
    {
        $user = new self();

        $user->setEmail($dto->getEmail());
        $user->setPassword($passwordHasher->hashPassword($user, $dto->getPassword()));
        $user->activate();
        $user->unverify();

        return $user;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @see UserInterface
     *
     * @throws \Exception
     */
    public function getUserIdentifier(): string
    {
        if (empty($this->email)) {
            throw new \Exception('Email is empty');
        }

        return $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return string[]
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param string[] $roles
     */
    public function setRoles(array $roles): void
    {
        $this->roles = $roles;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function activate(): void
    {
        $this->isActive = true;
    }

    public function deactivate(): void
    {
        $this->isActive = false;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function verify(): void
    {
        $this->isVerified = true;
    }

    public function unverify(): void
    {
        $this->isVerified = false;
    }

    public function eraseCredentials(): void
    {
    }
}
