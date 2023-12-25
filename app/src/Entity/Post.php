<?php

namespace App\Entity;

use App\Repository\PostRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostRepository::class)]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $schedule_date = null;

    #[ORM\ManyToOne(targetEntity: User::class, inversedBy: 'posts')]
    #[ORM\JoinColumn(name:'user_id',referencedColumnName: 'id',nullable: false)]

    private ?User $user = null;
    #[ORM\Column(type: Types::BOOLEAN, nullable: true )]
    private ?bool $publish_status = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $publish_at = null;

    public function getPublishAt(): string
    {
        return $this->publish_at?->format("Y-m-d H:i:s") ?? '';
    }

    public function setPublishAt(?string $publish_at): void
    {
        $this->publish_at = \DateTime::createFromFormat('Y-m-d H:i:s', $publish_at);

    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description)
    {
        $this->description = $description;

        return $this;
    }

    public function getScheduleDate(): string
    {
        return $this->schedule_date?->format("Y-m-d H:i:s") ?? '';
    }

    public function setScheduleDate(?string $schedule_date): static
    {
        $this->schedule_date = \DateTime::createFromFormat('Y-m-d H:i:s', $schedule_date);

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;
        return $this;
    }

    public function getPublishStatus(): ?bool
    {
        return $this->publish_status;
    }

    public function setPublishStatus(?bool $publish_status): void
    {
        $this->publish_status = $publish_status;
    }

    public function toArray(): array
    {
        return [
            'id' => $this->getId(),
            'title' => $this->getTitle(),
            'description' => $this->getDescription(),
            'schedule_date' => $this->getScheduleDate(),
            'publish_status' => $this->getPublishStatus(),
            'publish_at' => $this->getPublishAt(),
            'user' => [
                "id" => $this->getUser()->getId(),
                "email" => $this->getUser()->getEmail(),
                "name" => $this->getUser()->getFullName(),
            ],
        ];
    }

}
