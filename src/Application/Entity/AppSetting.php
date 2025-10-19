<?php

declare(strict_types=1);

namespace App\Application\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table(name: 'tblAppSetting', schema: 'Core')]
class AppSetting extends BaseEntity
{
    #[Id]
    #[GeneratedValue]
    #[Column(name: 'intAppSettingId')]
    protected ?int $id = null;

    #[Column(name: 'strAppSettingName', length: 255)]
    private string $name;

    #[Column(name: 'strAppSettingValue')]
    private string $value;

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getValue(): string
    {
        return $this->value;
    }

    public function setValue(string $value): void
    {
        $this->value = $value;
    }
}
