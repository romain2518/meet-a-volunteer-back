<?php

namespace App\Entity;

use App\Repository\ReceptionStructureRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReceptionStructureRepository::class)
 * @ORM\Table(indexes={@ORM\Index(name="reception_structure_name_idx", columns={"name"})})
 */
class ReceptionStructure
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64, unique=true)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=64, unique=true)
     */
    private $slugName;

    /**
     * @ORM\Column(type="datetime", columnDefinition="timestamp default current_timestamp")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true, columnDefinition="timestamp default current_timestamp on update current_timestamp")
     */
    private $updatedAt;

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

    public function getSlugName(): ?string
    {
        return $this->slugName;
    }

    public function setSlugName(string $slugName): self
    {
        $this->slugName = $slugName;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }
}
