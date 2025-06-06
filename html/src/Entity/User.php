<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`users`')]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 15)]
    private ?string $phone;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $name;

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    public function __construct(string $phone, string $name = null)
    {
        $this->phone = $phone;
        $this->name = $name;
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
}
