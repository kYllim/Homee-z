<?php

namespace App\Entity;

use App\Repository\HouseholdRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;

#[ApiResource]
#[ORM\Entity(repositoryClass: HouseholdRepository::class)]
class Household
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]

    private ?string $name = null;
    #[ORM\Column(length: 255)]
    private ?string $accessCode = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    /**
     * @var Collection<int, Event>
     */
    #[ORM\OneToMany(targetEntity: Event::class, mappedBy: 'household')]
    private Collection $events;

    /**
     * @var Collection<int, Chore>
     */
    #[ORM\OneToMany(targetEntity: Chore::class, mappedBy: 'household')]
    private Collection $chores;

    /**
     * @var Collection<int, ShoppingList>
     */
    #[ORM\OneToMany(targetEntity: ShoppingList::class, mappedBy: 'household')]
    private Collection $shoppingLists;

    /**
     * @var Collection<int, Recipe>
     */
    #[ORM\OneToMany(targetEntity: Recipe::class, mappedBy: 'household')]
    private Collection $recipes;

    /**
     * @var Collection<int, BudgetGoal>
     */
    #[ORM\OneToMany(targetEntity: BudgetGoal::class, mappedBy: 'household')]
    private Collection $budgetGoals;


    /**
     * @var Collection<int, PersonHousehold>
     */
    #[ORM\OneToMany(targetEntity: PersonHousehold::class, mappedBy: 'household')]
    private Collection $memberships;

    public function __construct()
    {
        $this->events = new ArrayCollection();
        $this->chores = new ArrayCollection();
        $this->shoppingLists = new ArrayCollection();
        $this->recipes = new ArrayCollection();
        $this->budgetGoals = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
        $this->memberships = new ArrayCollection();
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

    public function getAccessCode(): ?string
    {
        return $this->accessCode;
    }

    public function setAccessCode(string $accessCode): static
    {
        $this->accessCode = $accessCode;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): static
    {
        if (!$this->events->contains($event)) {
            $this->events->add($event);
            $event->setHousehold($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): static
    {
        if ($this->events->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getHousehold() === $this) {
                $event->setHousehold(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Chore>
     */
    public function getChores(): Collection
    {
        return $this->chores;
    }

    public function addChore(Chore $chore): static
    {
        if (!$this->chores->contains($chore)) {
            $this->chores->add($chore);
            $chore->setHousehold($this);
        }

        return $this;
    }

    public function removeChore(Chore $chore): static
    {
        if ($this->chores->removeElement($chore)) {
            // set the owning side to null (unless already changed)
            if ($chore->getHousehold() === $this) {
                $chore->setHousehold(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ShoppingList>
     */
    public function getShoppingLists(): Collection
    {
        return $this->shoppingLists;
    }

    public function addShoppingList(ShoppingList $shoppingList): static
    {
        if (!$this->shoppingLists->contains($shoppingList)) {
            $this->shoppingLists->add($shoppingList);
            $shoppingList->setHousehold($this);
        }

        return $this;
    }

    public function removeShoppingList(ShoppingList $shoppingList): static
    {
        if ($this->shoppingLists->removeElement($shoppingList)) {
            // set the owning side to null (unless already changed)
            if ($shoppingList->getHousehold() === $this) {
                $shoppingList->setHousehold(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Recipe>
     */
    public function getRecipes(): Collection
    {
        return $this->recipes;
    }

    public function addRecipe(Recipe $recipe): static
    {
        if (!$this->recipes->contains($recipe)) {
            $this->recipes->add($recipe);
            $recipe->setHousehold($this);
        }

        return $this;
    }

    public function removeRecipe(Recipe $recipe): static
    {
        if ($this->recipes->removeElement($recipe)) {
            // set the owning side to null (unless already changed)
            if ($recipe->getHousehold() === $this) {
                $recipe->setHousehold(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, BudgetGoal>
     */
    public function getBudgetGoals(): Collection
    {
        return $this->budgetGoals;
    }

    public function addBudgetGoal(BudgetGoal $budgetGoal): static
    {
        if (!$this->budgetGoals->contains($budgetGoal)) {
            $this->budgetGoals->add($budgetGoal);
            $budgetGoal->setHousehold($this);
        }

        return $this;
    }

    public function removeBudgetGoal(BudgetGoal $budgetGoal): static
    {
        if ($this->budgetGoals->removeElement($budgetGoal)) {
            // set the owning side to null (unless already changed)
            if ($budgetGoal->getHousehold() === $this) {
                $budgetGoal->setHousehold(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, PersonHousehold>
     */
    public function getMemberships(): Collection
    {
        return $this->memberships;
    }

    public function addMembership(PersonHousehold $membership): static
    {
        if (!$this->memberships->contains($membership)) {
            $this->memberships->add($membership);
            $membership->setHousehold($this);
        }

        return $this;
    }

    public function removeMembership(PersonHousehold $membership): static
    {
        if ($this->memberships->removeElement($membership)) {
            // set the owning side to null (unless already changed)
            if ($membership->getHousehold() === $this) {
                $membership->setHousehold(null);
            }
        }

        return $this;
    }
}
