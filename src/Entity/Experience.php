<?php

namespace App\Entity;

use App\Repository\ExperienceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
// @link https://symfony.com/doc/5.4/components/serializer.html#attributes-groups
use Symfony\Component\Validator\Constraints as Assert;
// @link https://symfony.com/doc/current/reference/forms/types.html

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
     * 
     * @Groups({
     *  "api_user_show",
     *  "api_experience_list",
     *  "api_experience_show"
     * })
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * 
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 5,
     *      max = 100
     * )
     * 
     * @Groups({
     *  "api_user_show",
     *  "api_experience_list",
     *  "api_experience_show"
     * })
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=100)
     * 
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 5,
     *      max = 100
     * )
     * 
     * @Groups({
     *  "api_user_show",
     *  "api_experience_list",
     *  "api_experience_show"
     * })
     */
    private $slugTitle;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 0,
     *      max = 255
     * )
     * 
     * @Groups({
     *  "api_user_show",
     *  "api_experience_list",
     *  "api_experience_show"
     * })
     */
    private $country;

    /**
     * @ORM\Column(type="string", length=255)
     * 
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 0,
     *      max = 255
     * )
     * 
     * @Groups({
     *  "api_user_show",
     *  "api_experience_list",
     *  "api_experience_show"
     * })
     */
    private $city;

    /**
     * @ORM\Column(type="integer")
     * 
     * @Assert\NotBlank
     * 
     * @Groups({
     *  "api_user_show",
     *  "api_experience_list",
     *  "api_experience_show"
     * })
     */
    private $year;

    /**
     * @ORM\Column(type="datetime")
     * 
     * @Assert\NotBlank
     * 
     * @Groups({
     *  "api_experience_show"
     * })
     */
    private $duration;

    /**
     * @ORM\Column(type="text")
     * 
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 5,
     *      max = 1500
     * )
     * 
     * @Groups({
     *  "api_experience_show"
     * })
     */
    private $feedback;

    /**
     * @ORM\Column(type="integer", options={"default": 0, "unsigned"=true})
     * 
     * @Groups({
     *  "api_user_show",
     *  "api_experience_list",
     *  "api_experience_show"
     * })
     */
    private $views;

    /**
     * @ORM\Column(type="string", length=64, options={"default": "0.jpg"})
     * 
     * @Groups({
     *  "api_user_show",
     *  "api_experience_list",
     *  "api_experience_show"
     * })
     */
    private $picture;

    /**
     * @ORM\Column(type="integer", options={"unsigned"=true})
     * 
     * @Assert\NotBlank
     * @Assert\Range(
     *      min = 0,
     *      max = 4294967295
     * )
     * 
     * @Groups({
     *  "api_experience_show"
     * })
     */
    private $participation_fee;

    /**
     * @ORM\Column(type="string", length=64)
     * 
     * @Assert\NotBlank
     * @Assert\Choice({"Yes", "No", "Partially"})
     * 
     * @Groups({
     *  "api_experience_show"
     * })
     */
    private $isHosted;

    /**
     * @ORM\Column(type="string", length=64)
     * 
     * @Assert\NotBlank
     * @Assert\Choice({"Yes", "No", "Partially"})
     * 
     * @Groups({
     *  "api_experience_show"
     * })
     */
    private $isFed;

    /**
     * @ORM\Column(type="json")
     * 
     * @Assert\NotBlank
     * @Assert\Count(
     *      min = 1,
     *      max = 2
     * )
     * 
     * @Groups({
     *  "api_experience_show"
     * })
     */
    private $language = [];

    /**
     * @ORM\Column(type="datetime")
     * 
     * @Groups({
     *  "api_user_show",
     *  "api_experience_list",
     *  "api_experience_show"
     * })
     * 
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * 
     * @Groups({
     *  "api_user_show",
     *  "api_experience_list",
     *  "api_experience_show"
     * })
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="experiences")
     * @ORM\JoinColumn(nullable=false)
     * 
     * @Groups({
     *  "api_experience_list",
     *  "api_experience_show"
     * })
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=VolunteeringType::class, inversedBy="experiences")
     * 
     * @Groups({
     *  "api_user_show",
     *  "api_experience_list",
     *  "api_experience_show"
     * })
     */
    private $volunteeringType;

    /**
     * @ORM\ManyToOne(targetEntity=ReceptionStructure::class, inversedBy="experiences")
     * 
     * @Groups({
     *  "api_user_show",
     *  "api_experience_list",
     *  "api_experience_show"
     * })
     */
    private $receptionStructure;

    /**
     * @ORM\ManyToMany(targetEntity=Thematic::class, inversedBy="experiences")
     * 
     * @Groups({
     *  "api_user_show",
     *  "api_experience_list",
     *  "api_experience_show"
     * })
     */
    private $thematic;

    public function __construct()
    {
        $this->thematic = new ArrayCollection();
    }

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

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): self
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

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getVolunteeringType(): ?VolunteeringType
    {
        return $this->volunteeringType;
    }

    public function setVolunteeringType(?VolunteeringType $volunteeringType): self
    {
        $this->volunteeringType = $volunteeringType;

        return $this;
    }

    public function getReceptionStructure(): ?ReceptionStructure
    {
        return $this->receptionStructure;
    }

    public function setReceptionStructure(?ReceptionStructure $receptionStructure): self
    {
        $this->receptionStructure = $receptionStructure;

        return $this;
    }

    /**
     * @return Collection<int, Thematic>
     */
    public function getThematic(): Collection
    {
        return $this->thematic;
    }

    public function addThematic(Thematic $thematic): self
    {
        if (!$this->thematic->contains($thematic)) {
            $this->thematic[] = $thematic;
        }

        return $this;
    }

    public function removeThematic(Thematic $thematic): self
    {
        $this->thematic->removeElement($thematic);

        return $this;
    }
}
