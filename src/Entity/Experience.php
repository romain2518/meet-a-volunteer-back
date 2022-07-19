<?php

namespace App\Entity;

use App\Repository\ExperienceRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ExperienceRepository::class)
 * @ORM\Table(indexes={@ORM\Index(name="search_idx", columns={"country","city"})})
 */
class Experience
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $slugTitle;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $country;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $city;

    /**
     * @ORM\Column(type="date", columnDefinition="YEAR")
     *
     */
    private $year;

    /**
     * @ORM\Column(type="date", columnDefinition="timestamp")
     * 
     */
    private $duration;

    /**
     * @ORM\Column(type="text")
     * 
     */
    private $feedback;

    /**
     * @ORM\Column(type="integer", options={"default": 0, "unsigned"=true})
     * 
     */
    private $views;

    /**
     * @ORM\Column(type="string", length=64, options={"default": "0.png"})
     */
    private $picture;

    /**
     * @ORM\Column(type="integer", options={"unsigned"=true})
     */
    private $participation_fee;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $isHosted;

    /**
     * @ORM\Column(type="string", length=64)
     */
    private $isFed;

    /**
     * @ORM\Column(type="json")
     */
    private $language = [];

    /**
     * @ORM\Column(type="date", columnDefinition="timestamp default current_timestamp")
     * 
     */
    private $createdAt;

    /**
     * @ORM\Column(type="date", nullable=true, columnDefinition="timestamp default current_timestamp on update current_timestamp")
     */
    private $updatedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getSlugTitle(): ?string
    {
        return $this->slugTitle;
    }

    public function setSlugTitle(string $slugTitle): self
    {
        $this->slugTitle = $slugTitle;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getYear(): ?\DateTimeInterface
    {
        return $this->year;
    }

    public function setYear(\DateTimeInterface $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getDuration(): ?\DateTimeInterface
    {
        return $this->duration;
    }

    public function setDuration(\DateTimeInterface $duration): self
    {
        $this->duration = $duration;

        return $this;
    }

    public function getFeedback(): ?string
    {
        return $this->feedback;
    }

    public function setFeedback(string $feedback): self
    {
        $this->feedback = $feedback;

        return $this;
    }

    public function getViews(): ?int
    {
        return $this->views;
    }

    public function setViews(int $views): self
    {
        $this->views = $views;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getParticipationFee(): ?int
    {
        return $this->participation_fee;
    }

    public function setParticipationFee(int $participation_fee): self
    {
        $this->participation_fee = $participation_fee;

        return $this;
    }

    public function getIsHosted(): ?string
    {
        return $this->isHosted;
    }

    public function setIsHosted(string $isHosted): self
    {
        $this->isHosted = $isHosted;

        return $this;
    }

    public function getIsFed(): ?string
    {
        return $this->isFed;
    }

    public function setIsFed(string $isFed): self
    {
        $this->isFed = $isFed;

        return $this;
    }

    public function getLanguage(): ?array
    {
        return $this->language;
    }

    public function setLanguage(array $language): self
    {
        $this->language = $language;

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
