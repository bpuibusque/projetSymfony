<?php

namespace App\Entity;

use App\Repository\CommissionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: CommissionRepository::class)]
class Commission
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    #[ORM\OneToMany(targetEntity: UserCommissionSubscription::class, mappedBy: 'commission')]
    private Collection $userCommissionSubscriptions;

    #[ORM\OneToMany(targetEntity: Post::class, mappedBy: 'commission')]
    private Collection $posts;

    public function __construct()
    {
        $this->userCommissionSubscriptions = new ArrayCollection();
        $this->posts = new ArrayCollection();
        $this->createdAt = new \DateTime(); 
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection<int, UserCommissionSubscription>
     */
    public function getUserCommissionSubscriptions(): Collection
    {
        return $this->userCommissionSubscriptions;
    }

    public function addUserCommissionSubscription(UserCommissionSubscription $userCommissionSubscription): static
    {
        if (!$this->userCommissionSubscriptions->contains($userCommissionSubscription)) {
            $this->userCommissionSubscriptions->add($userCommissionSubscription);
            $userCommissionSubscription->setCommission($this);
        }

        return $this;
    }

    public function removeUserCommissionSubscription(UserCommissionSubscription $userCommissionSubscription): static
    {
        if ($this->userCommissionSubscriptions->removeElement($userCommissionSubscription)) {
            // set the owning side to null (unless already changed)
            if ($userCommissionSubscription->getCommission() === $this) {
                $userCommissionSubscription->setCommission(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Post>
     */
    public function getPosts(): Collection
    {
        return $this->posts;
    }

    public function addPost(Post $post): static
    {
        if (!$this->posts->contains($post)) {
            $this->posts->add($post);
            $post->setCommission($this);
        }

        return $this;
    }

    public function removePost(Post $post): static
    {
        if ($this->posts->removeElement($post)) {
            if ($post->getCommission() === $this) {
                $post->setCommission(null);
            }
        }

        return $this;
    }
}
