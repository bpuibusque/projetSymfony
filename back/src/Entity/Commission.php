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

    #[ORM\OneToMany(mappedBy: 'commission', targetEntity: UserCommissionSubscription::class)]
    private Collection $userCommissionSubscriptions;

    #[ORM\OneToMany(mappedBy: 'commission', targetEntity: Post::class)]
    private Collection $posts;

    #[ORM\OneToMany(mappedBy: 'commission', targetEntity: Privilege::class)]
    private Collection $privileges;

    public function __construct()
    {
        $this->userCommissionSubscriptions = new ArrayCollection();
        $this->posts = new ArrayCollection();
        $this->privileges = new ArrayCollection();
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

    public function setName(string $name): self
    {
        $this->name = $name;
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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
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

    public function addUserCommissionSubscription(UserCommissionSubscription $userCommissionSubscription): self
    {
        if (!$this->userCommissionSubscriptions->contains($userCommissionSubscription)) {
            $this->userCommissionSubscriptions[] = $userCommissionSubscription;
            $userCommissionSubscription->setCommission($this);
        }

        return $this;
    }

    public function removeUserCommissionSubscription(UserCommissionSubscription $userCommissionSubscription): self
    {
        if ($this->userCommissionSubscriptions->removeElement($userCommissionSubscription)) {
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

    public function addPost(Post $post): self
    {
        if (!$this->posts->contains($post)) {
            $this->posts[] = $post;
            $post->setCommission($this);
        }

        return $this;
    }

    public function removePost(Post $post): self
    {
        if ($this->posts->removeElement($post)) {
            if ($post->getCommission() === $this) {
                $post->setCommission(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Privilege>
     */
    public function getPrivileges(): Collection
    {
        return $this->privileges;
    }

    public function addPrivilege(Privilege $privilege): self
    {
        if (!$this->privileges->contains($privilege)) {
            $this->privileges[] = $privilege;
            $privilege->setCommission($this);
        }

        return $this;
    }

    public function removePrivilege(Privilege $privilege): self
    {
        if ($this->privileges->removeElement($privilege)) {
            if ($privilege->getCommission() === $this) {
                $privilege->setCommission(null);
            }
        }

        return $this;
    }
}
