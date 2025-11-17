<?php

declare(strict_types=1);

namespace App\Auth\Classes\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class RegistrationDTO
{
    #[Assert\NotBlank]
    #[Assert\Email]
    private string $email = '';

    #[Assert\NotBlank]
    #[Assert\Length(min: 8, minMessage: 'Your password should be at least {{ limit }} characters long')]
    private string $password = '';

    #[Assert\NotBlank]
    private string $confirmPassword = '';

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    public function getConfirmPassword(): string
    {
        return $this->confirmPassword;
    }

    public function setConfirmPassword(string $confirmPassword): static
    {
        $this->confirmPassword = $confirmPassword;
        return $this;
    }

    public function passwordsMatch(): bool
    {
        return $this->password === $this->confirmPassword;
    }
}
