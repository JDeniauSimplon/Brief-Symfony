<?php

namespace App\Entity;

use App\Repository\RuleRideRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: RuleRideRepository::class)]
class RuleRide
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToMany(targetEntity: rule::class, inversedBy: 'ride_id')]
    private Collection $rule_id;

    #[ORM\Column]
    private ?int $Ride_Id = null;

    public function __construct()
    {
        $this->rule_id = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, rule>
     */
    public function getRuleId(): Collection
    {
        return $this->rule_id;
    }

    public function addRuleId(rule $ruleId): self
    {
        if (!$this->rule_id->contains($ruleId)) {
            $this->rule_id->add($ruleId);
        }

        return $this;
    }

    public function removeRuleId(rule $ruleId): self
    {
        $this->rule_id->removeElement($ruleId);

        return $this;
    }

    public function getRideId(): ?int
    {
        return $this->Ride_Id;
    }

    public function setRideId(int $Ride_Id): self
    {
        $this->Ride_Id = $Ride_Id;

        return $this;
    }
}
