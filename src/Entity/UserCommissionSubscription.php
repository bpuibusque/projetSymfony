<?php

namespace App\Entity;

use App\Repository\UserCommissionSubscriptionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserCommissionSubscriptionRepository::class)]
class UserCommissionSubscription
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'userCommissionSubscriptions')]
    private ?User $user = null;

    #[ORM\ManyToOne(inversedBy: 'userCommissionSubscriptions')]
    private ?Commission $comission = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getCommission(): ?Commission
    {
        return $this->comission;
    }

    public function setCommission(?Commission $comission): static
    {
        $this->comission = $comission;

        return $this;
    }
}
