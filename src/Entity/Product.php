<?php

namespace App\Entity;

use App\Repository\ProductRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Type(
        type: 'string',
        message: 'The value is not valid',
    )]
    #[Assert\Length(
        min: 3,
        max: 225,
        minMessage: 'product name must be at least {{ limit }} characters long',
        maxMessage: 'product name cannot be longer than {{ limit }} characters',
    )]
    private ?string $name = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    #[Assert\NotBlank]
    #[Assert\Type(
        type: 'numeric',
        message: 'The value is not valid',
    )]
    #[Assert\Length(
        min: 1,
        max: 11,
        minMessage: 'product price must be at least {{ limit }} characters long',
        maxMessage: 'product price cannot be longer than {{ limit }} characters',
    )]
    private ?string $price = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\NotBlank]
    #[Assert\Type(
        type: 'integer',
        message: 'The value is not valid',
    )]
    #[Assert\Length(
        min: 1,
        max: 11,
        minMessage: 'product stock must be at least {{ limit }} characters long',
        maxMessage: 'product stock cannot be longer than {{ limit }} characters',
    )]
    private ?int $stock = null;

    #[ORM\Column(type: Types::TEXT)]
    #[Assert\NotBlank]
    #[Assert\NotBlank]
    #[Assert\Type(
        type: 'string',
        message: 'The value is not valid',
    )]
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: 'product description must be at least {{ limit }} characters long',
        maxMessage: 'product description cannot be longer than {{ limit }} characters',
    )]
    private ?string $description = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPrice(): ?string
    {
        return $this->price;
    }

    public function setPrice(string $price): self
    {
        $this->price = $price;

        return $this;
    }

    public function getStock(): ?int
    {
        return $this->stock;
    }

    public function setStock(int $stock): self
    {
        $this->stock = $stock;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }
}
