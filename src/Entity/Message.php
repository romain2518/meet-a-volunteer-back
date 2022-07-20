<?php

namespace App\Entity;

use App\Repository\MessageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=MessageRepository::class)
 */
class Message
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * 
     * @Groups({
     *  "api_message_list",
     *  "api_message_show"
     * })
     */
    private $id;

    /**
     * @ORM\Column(type="text")
     * 
     * @Groups({
     *  "api_message_list",
     *  "api_message_show"
     * })
     */
    private $message;

    /**
     * @ORM\Column(type="boolean", options={"default": 0})
     * 
     * @Assert\NotBlank
     * @Assert\Length(
     *      min = 5,
     *      max = 250
     * )
     * 
     * @Groups({
     *  "api_message_list",
     *  "api_message_show"
     * })
     */
    private $isRead;

    /**
     * @ORM\Column(type="datetime", columnDefinition="timestamp default current_timestamp")
     * 
     * @Groups({
     *  "api_message_list",
     *  "api_message_show"
     * })
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true, columnDefinition="timestamp default current_timestamp on update current_timestamp")
     * 
     * @Groups({
     *  "api_message_list",
     *  "api_message_show"
     * })
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="sentMessages")
     * @ORM\JoinColumn(nullable=true)
     * 
     * @Groups({
     *  "api_message_list",
     *  "api_message_show"
     * })
     */
    private $userSender;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="receivedMessages")
     * @ORM\JoinColumn(nullable=true)
     * 
     * @Groups({
     *  "api_message_list",
     *  "api_message_show"
     * })
     */
    private $userReceiver;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMessage(): ?string
    {
        return $this->message;
    }

    public function setMessage(string $message): self
    {
        $this->message = $message;

        return $this;
    }

    public function isIsRead(): ?bool
    {
        return $this->isRead;
    }

    public function setIsRead(bool $isRead): self
    {
        $this->isRead = $isRead;

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

    public function getUserSender(): ?User
    {
        return $this->userSender;
    }

    public function setUserSender(?User $userSender): self
    {
        $this->userSender = $userSender;

        return $this;
    }

    public function getUserReceiver(): ?User
    {
        return $this->userReceiver;
    }

    public function setUserReceiver(?User $userReceiver): self
    {
        $this->userReceiver = $userReceiver;

        return $this;
    }
}
