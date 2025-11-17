<?php

declare(strict_types=1);

namespace App\Application\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\MappedSuperclass;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;

#[MappedSuperclass]
#[HasLifecycleCallbacks]
abstract class AbstractBaseEntity
{
    protected ?int $id = null;

    #[Column(name: 'dtmCreated', type: 'datetime')]
    protected \DateTimeInterface $createdAt;

    #[Column(name: 'dtmUpdated', type: 'datetime')]
    protected ?\DateTimeInterface $updatedAt = null;

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

    #[PrePersist]
    public function prePersist(): void
    {
        $this->createdAt = new \DateTime();
    }

    #[PreUpdate]
    public function preUpdate(): void
    {
        $this->updatedAt = new \DateTime();
    }
}
