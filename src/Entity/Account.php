<?php

namespace App\Entity;

use App\Repository\AccountRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AccountRepository::class)]
class Account
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(type: 'string', length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: 'string', length: 100)]
    private ?string $type = null;

    #[ORM\Column(type: 'string', length: 50)]
    private ?string $expiration = null;

    public function __construct(
        ?string $name,
        ?string $type,
        ?string $expiration
    ) {
        $this->name = $name;
        $this->type = $type;
        $this->expiration = $expiration;
    }


    public function getId(): ?int
    {
        return $this->id;
    }
    public function setId(int $id): self
    {
        $this->id = $id;
        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;
        return $this;
    }

    public function getExpiration(): ?string
    {
        return $this->expiration;
    }

    public function setExpiration(string $expiration): self
    {
        $this->expiration = $expiration;
        return $this;
    }
}
