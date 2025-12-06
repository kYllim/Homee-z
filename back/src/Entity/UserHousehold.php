<?php

namespace App\Entity;

use App\Repository\UserHouseholdRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use App\Enum\HouseHoldEnum;


#[ApiResource]
#[ORM\Entity(repositoryClass: UserHouseholdRepository::class)]
class UserHousehold
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

   #[ORM\Column(type: 'string', enumType: HouseHoldEnum::class)]
    private ?HouseHoldEnum $role = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'userHouseholds')]
    private Collection $member;

    #[ORM\ManyToOne(inversedBy: 'members')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Household $houseHold = null;

    public function __construct()
    {
        $this->member = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(HouseHoldEnum $role): static
    {
        $this->role = $role;
        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getMember(): Collection
    {
        return $this->member;
    }

    public function addMember(User $member): static
    {
        if (!$this->member->contains($member)) {
            $this->member->add($member);
        }

        return $this;
    }

    public function removeMember(User $member): static
    {
        $this->member->removeElement($member);

        return $this;
    }

    public function getHouseHold(): ?Household
    {
        return $this->houseHold;
    }

    public function setHouseHold(?Household $houseHold): static
    {
        $this->houseHold = $houseHold;

        return $this;
    }
}
