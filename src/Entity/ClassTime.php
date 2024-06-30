<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ClassTimeRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ClassTimeRepository::class)]

class ClassTime
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 20)]
    private ?string $classTime = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getClassTime(): ?string
    {
        return $this->classTime;
    }
    public function setClassTime(string $classTime): static
    {
        $this->classTime = $classTime;
        return $this;
    }
}
