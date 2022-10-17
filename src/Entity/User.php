<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @ORM\Table(indexes={@ORM\Index(name="native_country_idx", columns={"native_country"})})
 * @UniqueEntity("pseudo")
 * @UniqueEntity("pseudoSlug")
 * @UniqueEntity("email")
 * @ORM\HasLifecycleCallbacks
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * 
     * @Groups({
     *  "api_user_list",
     *  "api_user_show",
     *  "api_message_list",
     *  "api_message_show",
     *  "api_experience_list",
     *  "api_experience_show"
     * })
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * 
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 3,
     *      max = 30
     * )
     * 
     * @Groups({
     *  "api_user_list",
     *  "api_user_show",
     *  "api_message_list",
     *  "api_message_show",
     *  "api_experience_list",
     *  "api_experience_show"
     * })
     */
    private $pseudo;

    /**
     * @ORM\Column(type="json")
     * 
     * @Groups({
     *  "api_user_list",
     *  "api_user_show"
     * })
     */
    private $roles = ['ROLE_USER'];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=30, unique=true)
     * 
     * @Groups({
     *  "api_user_list",
     *  "api_user_show",
     *  "api_message_list",
     *  "api_message_show",
     *  "api_experience_list",
     *  "api_experience_show"
     * })
     */
    private $pseudoSlug;

    /**
     * @ORM\Column(type="string", length=64)
     * 
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 1,
     *      max = 64
     * )
     * 
     * @Groups({
     *  "api_user_list",
     *  "api_user_show",
     *  "api_message_list",
     *  "api_message_show",
     *  "api_experience_list",
     *  "api_experience_show"
     * })
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=64)
     * 
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 1,
     *      max = 64
     * )
     * 
     * @Groups({
     *  "api_user_list",
     *  "api_user_show",
     *  "api_message_list",
     *  "api_message_show",
     *  "api_experience_list",
     *  "api_experience_show"
     * })
     */
    private $lastname;

    /**
     * @ORM\Column(type="datetime")
     * 
     * @Assert\NotBlank
     * @Assert\Range(
     *      min = "-100 years",
     *      max = "-13 years"
     * )
     * 
     * @Groups({
     *  "api_user_list",
     *  "api_user_show"
     * })
     */
    private $age;

    /**
     * @ORM\Column(type="string", length=64)
     * 
     * @Groups({
     *  "api_user_list",
     *  "api_user_show",
     *  "api_message_list",
     *  "api_message_show",
     *  "api_experience_list",
     *  "api_experience_show"
     * })
     */
    private $profilePicture = '0.png';

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * 
     * @Assert\NotBlank
     * 
     * @Groups({
     *  "api_user_list",
     *  "api_user_show"
     * })
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * 
     * @Groups({
     *  "api_user_list",
     *  "api_user_show"
     * })
     */
    private $phone;

    /**
     * @ORM\Column(type="string", length=250, nullable=true)
     * 
     * @Assert\Length(
     *      min = 0,
     *      max = 250
     * )
     * 
     * @Groups({
     *  "api_user_list",
     *  "api_user_show"
     * })
     */
    private $biography;

    /**
     * @ORM\Column(type="string", length=190)
     * 
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 0,
     *      max = 190
     * )
     * 
     * @Groups({
     *  "api_user_list",
     *  "api_user_show"
     * })
     */
    private $nativeCountry;

    /**
     * @ORM\Column(type="datetime")
     * 
     * @Groups({
     *  "api_user_list",
     *  "api_user_show"
     * })
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     * 
     * @Groups({
     *  "api_user_list",
     *  "api_user_show"
     * })
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="userSender")
     */
    private $sentMessages;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="userReceiver")
     */
    private $receivedMessages;

    /**
     * @ORM\OneToMany(targetEntity=Experience::class, mappedBy="user")
     * 
     * @Groups({
     *  "api_user_show"
     * })
     */
    private $experiences;

    public function __construct()
    {
        $this->sentMessages = new ArrayCollection();
        $this->receivedMessages = new ArrayCollection();
        $this->experiences = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    public function setPseudo(string $pseudo): self
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->pseudo;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->pseudo;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getPseudoSlug(): ?string
    {
        return $this->pseudoSlug;
    }

    public function setPseudoSlug(string $pseudoSlug): self
    {
        $this->pseudoSlug = $pseudoSlug;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getAge(): ?\DateTimeInterface
    {
        return $this->age;
    }

    public function setAge(\DateTimeInterface $age): self
    {
        $this->age = $age;

        return $this;
    }

    public function getProfilePicture(): ?string
    {
        return $this->profilePicture;
    }

    public function setProfilePicture(string $profilePicture): self
    {
        $this->profilePicture = $profilePicture;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getBiography(): ?string
    {
        return $this->biography;
    }

    public function setBiography(?string $biography): self
    {
        $this->biography = $biography;

        return $this;
    }

    public function getNativeCountry(): ?string
    {
        return $this->nativeCountry;
    }

    public function setNativeCountry(string $nativeCountry): self
    {
        $this->nativeCountry = $nativeCountry;

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

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
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
     * @return Collection<int, Message>
     */
    public function getSentMessages(): Collection
    {
        return $this->sentMessages;
    }

    public function addSentMessage(Message $sentMessage): self
    {
        if (!$this->sentMessages->contains($sentMessage)) {
            $this->sentMessages[] = $sentMessage;
            $sentMessage->setUserSender($this);
        }

        return $this;
    }

    public function removeSentMessage(Message $sentMessage): self
    {
        if ($this->sentMessages->removeElement($sentMessage)) {
            // set the owning side to null (unless already changed)
            if ($sentMessage->getUserSender() === $this) {
                $sentMessage->setUserSender(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getReceivedMessages(): Collection
    {
        return $this->receivedMessages;
    }

    public function addReceivedMessage(Message $receivedMessage): self
    {
        if (!$this->receivedMessages->contains($receivedMessage)) {
            $this->receivedMessages[] = $receivedMessage;
            $receivedMessage->setUserReceiver($this);
        }

        return $this;
    }

    public function removeReceivedMessage(Message $receivedMessage): self
    {
        if ($this->receivedMessages->removeElement($receivedMessage)) {
            // set the owning side to null (unless already changed)
            if ($receivedMessage->getUserReceiver() === $this) {
                $receivedMessage->setUserReceiver(null);
            }
        }

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
            $experience->setUser($this);
        }

        return $this;
    }

    public function removeExperience(Experience $experience): self
    {
        if ($this->experiences->removeElement($experience)) {
            // set the owning side to null (unless already changed)
            if ($experience->getUser() === $this) {
                $experience->setUser(null);
            }
        }

        return $this;
    }
}