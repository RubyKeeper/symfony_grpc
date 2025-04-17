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
    private ?string $phone;

    #[ORM\Column]
    private ?\DateTimeImmutable $blockAt;

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
}
