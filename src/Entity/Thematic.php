<?php

namespace App\Entity;

use App\Repository\ThematicRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=ThematicRepository::class)
 * @ORM\Table(indexes={@ORM\Index(name="thematic_name_idx", columns={"name"})})
 * @UniqueEntity("name")
 * @UniqueEntity("slugName")
 * @ORM\HasLifecycleCallbacks
 */
class Thematic
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * 
     * @Groups({
     *  "api_user_show",
     *  "api_experience_list",
     *  "api_experience_show",
     *  "api_thematic_list"
     * })
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=64, unique=true)
     * 
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 5,
     *      max = 64
     * )
     * 
     * @Groups({
     *  "api_user_show",
     *  "api_experience_list",
     *  "api_experience_show",
     *  "api_thematic_list"
     * })
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=64, unique=true)
     * 
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 5,
     *      max = 64
     * )
     * 
     * @Groups({
     *  "api_user_show",
     *  "api_experience_list",
     *  "api_experience_show",
     *  "api_thematic_list"
     * })
     */
    private $slugName;

    /**
     * @ORM\Column(type="datetime")
     * 
     * @Groups({
     *  "api_thematic_list"
     * })
     */
    private $createdAt;

    /**
     * @ORM\Column(type="date", nullable=true)
     * 
     * @Groups({
     *  "api_thematic_list"
     * })
     */
    private $updatedAt;

    /**
     * @ORM\ManyToMany(targetEntity=Experience::class, mappedBy="thematic", cascade={"persist"})
     */
    private $experiences;

    public function __construct()
    {
        $this->experiences = new ArrayCollection();
    }

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

    /**
     * @ORM\PrePersist
     * @ORM\PreUpdate
     */
    public function updatedTimestamps(): void
    {
        if ($this->getCreatedAt() === null) { // => PrePersist
            
            $this->setCreatedAt(new \DateTime('now'));
        } else { // => PreUpdate

            $this->setUpdatedAt(new \DateTime('now'));
        } 
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

    /**
     * @return Collection<int, Experience>
     */
    public function getExperiences(): Collection
    {
        return $this->experiences;
    }

    public function addExperience(Experience $experience): self
    {
        if (!$this->experiences->contains($experience)) {
            $this->experiences[] = $experience;
            $experience->addThematic($this);
        }

        return $this;
    }

    public function removeExperience(Experience $experience): self
    {
        if ($this->experiences->removeElement($experience)) {
            $experience->removeThematic($this);
        }

        return $this;
    }
}
