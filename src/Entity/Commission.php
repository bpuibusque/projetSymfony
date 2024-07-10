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
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $createdAt = null;

    /**
     * @var Collection<int, UserCommissionSubscription>
     */
    #[ORM\OneToMany(targetEntity: UserCommissionSubscription::class, mappedBy: 'comission')]
    private Collection $userCommissionSubscriptions;

    public function __construct()
    {
        $this->userCommissionSubscriptions = new ArrayCollection();
    }

    /**
     * @var Collection<int, Post>
     */
    #[ORM\OneToMany(targetEntity: Post::class, mappedBy: 'comission')]


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
     * @return Collection<int, Post>
     */

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
            $userCommissionSubscription->setComission($this);
        }

        return $this;
    }

    public function removeUserCommissionSubscription(UserCommissionSubscription $userCommissionSubscription): static
    {
        if ($this->userCommissionSubscriptions->removeElement($userCommissionSubscription)) {
            // set the owning side to null (unless already changed)
            if ($userCommissionSubscription->getComission() === $this) {
                $userCommissionSubscription->setComission(null);
            }
        }

        return $this;
    }

}
