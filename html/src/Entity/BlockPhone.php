<?php

namespace App\Entity;

use App\Repository\BlockPhoneRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BlockPhoneRepository::class)]
class BlockPhone
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 255)]
    private ?string $phone = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $blockAt = null;

    /**
     * @param string|null $phone
     */
    public function __construct(?string $phone)
    {
        $this->phone = $phone;
        $this->blockAt = new \DateTimeImmutable('+ 1 hour');
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getBlockAt(): ?\DateTimeImmutable
    {
        return $this->blockAt;
    }

    public function setBlockAt(\DateTimeImmutable $blockAt): static
    {
        $this->blockAt = $blockAt;

        return $this;
    }
}
