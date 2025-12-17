<?php

namespace App\Entity;

use App\Enum\HouseHoldEnum;
use App\Repository\PersonHouseholdRepository;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PersonHouseholdRepository::class)]
#[ORM\Table(
    name: 'person_household',
    uniqueConstraints: [
        new UniqueConstraint(
            name: 'uniq_person_household',
            columns: ['person_id', 'household_id']
        )
    ]
)]
class PersonHousehold
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'memberships')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Person $person = null;

    #[ORM\ManyToOne(inversedBy: 'memberships')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Household $household = null;

    #[ORM\Column(enumType: HouseHoldEnum::class)]
    private ?HouseHoldEnum $role = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $joinedAt = null;

    public function __construct()
    {
        $this->joinedAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPerson(): ?Person
    {
        return $this->person;
    }

    public function setPerson(?Person $person): static
    {
        $this->person = $person;

        return $this;
    }

    public function getHousehold(): ?Household
    {
        return $this->household;
    }

    public function setHousehold(?Household $household): static
    {
        $this->household = $household;

        return $this;
    }

    public function getRole(): ?HouseHoldEnum
    {
        return $this->role;
    }

    public function setRole(HouseHoldEnum $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function getJoinedAt(): ?\DateTimeImmutable
    {
        return $this->joinedAt;
    }

    public function setJoinedAt(\DateTimeImmutable $joinedAt): static
    {
        $this->joinedAt = $joinedAt;

        return $this;
    }
}
