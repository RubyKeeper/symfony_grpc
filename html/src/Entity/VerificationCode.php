<?php

namespace App\Entity;

use App\Repository\VerificationCodeRepository;
use Doctrine\ORM\Mapping as ORM;
use Random\RandomException;

#[ORM\Entity(repositoryClass: VerificationCodeRepository::class)]
class VerificationCode
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private int $id;

    #[ORM\Column(length: 15)]
    private ?string $phone;

    #[ORM\Column]
    private ?int $code;

    #[ORM\Column(length: 255)]
    private ?string $token;

    #[ORM\Column]
    private \DateTimeImmutable $createdAt;

    #[ORM\Column]
    private ?int $count;

    /**
     * @throws RandomException
     */
    public function __construct($phone)
    {
        $this->phone = $phone;
        $this->code = rand(1000, 9999);
        $this->token = bin2hex(random_bytes(16));
        $this->createdAt = new \DateTimeImmutable();
        $this->count = 1;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function getCode(): ?int
    {
        // если прошло больше 1 минуты, то генерируем новый код
        if ((new \DateTimeImmutable('- 1 minute'))->getTimestamp()
            > $this->createdAt->getTimestamp()
        ) {
            $this->code = rand(1000, 9999);
            $this->count++;
        }
        return $this->code;
    }

    public function setCode(int $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getCount(): ?int
    {
        return $this->count;
    }
}
