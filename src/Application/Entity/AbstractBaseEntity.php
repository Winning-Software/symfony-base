<?php

declare(strict_types=1);

namespace App\Application\Entity;

use Doctrine\ORM\Mapping\Column;

abstract class AbstractBaseEntity
{
    protected ?int $id = null;

    #[Column(name: 'dtmCreated', type: 'datetime')]
    protected \DateTimeInterface $createdAt;

    #[Column(name: 'dtmUpdated', type: 'datetime')]
    protected ?\DateTimeInterface $updatedAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTime();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeInterface $updatedAt): void
    {
        $this->updatedAt = $updatedAt;
    }
}
