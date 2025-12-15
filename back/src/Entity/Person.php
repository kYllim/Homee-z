<?php

namespace App\Entity;

use App\Enum\PersonEnum;
use App\Repository\PersonRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PersonRepository::class)]
class Person
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    private ?string $lastName = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(enumType: PersonEnum::class)]
    private ?PersonEnum $type = null;

    #[ORM\OneToOne(mappedBy: 'person')]
    private ?User $user = null;

    /**
     * @var Collection<int, PersonHousehold>
     */
    #[ORM\OneToMany(targetEntity: PersonHousehold::class, mappedBy: 'person')]
    private Collection $memberships;


    /**
     * @var Collection<int, Event>
     */
    #[ORM\OneToMany(targetEntity: Event::class, mappedBy: 'creator')]
    private Collection $events;

    /**
     * @var Collection<int, Chore>
     */
    #[ORM\OneToMany(targetEntity: Chore::class, mappedBy: 'creator')]
    private Collection $chores;

    /**
     * @var Collection<int, ShoppingList>
     */
    #[ORM\OneToMany(targetEntity: ShoppingList::class, mappedBy: 'creator')]
    private Collection $shoppingLists;

    /**
     * @var Collection<int, Recipe>
     */
    #[ORM\OneToMany(targetEntity: Recipe::class, mappedBy: 'creator')]
    private Collection $recipes;

    /**
     * @var Collection<int, BudgetGoal>
     */
    #[ORM\OneToMany(targetEntity: BudgetGoal::class, mappedBy: 'creator')]
    private Collection $budgetGoals;

    /**
     * @var Collection<int, Alert>
     */
    #[ORM\OneToMany(targetEntity: Alert::class, mappedBy: 'member')]
    private Collection $alerts;

    /**
     * @var Collection<int, Viewer>
     */
    #[ORM\OneToMany(targetEntity: Viewer::class, mappedBy: 'member')]
    private Collection $viewers;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->memberships = new ArrayCollection();
        $this->events = new ArrayCollection();
        $this->chores = new ArrayCollection();
        $this->shoppingLists = new ArrayCollection();
        $this->recipes = new ArrayCollection();
        $this->budgetGoals = new ArrayCollection();
        $this->alerts = new ArrayCollection();
        $this->viewers = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $first_name): static
    {
        $this->firstName = $first_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $last_name): static
    {
        $this->lastName = $last_name;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUserType(): ?PersonEnum
    {
        return $this->type;
    }

    public function setUserType(PersonEnum $user_type): static
    {
        $this->type = $user_type;

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

    /**
     * @var Collection<int, PersonHousehold>
     */

    public function getMemberships(): Collection
    {
        return $this->memberships;
    }

    public function addMembership(PersonHousehold $membership): static
    {
        if (!$this->memberships->contains($membership)) {
            $this->memberships->add($membership);
            $membership->setPerson($this);
        }

        return $this;
    }

    public function removeMembership(PersonHousehold $membership): static
    {
        if ($this->memberships->removeElement($membership)) {
            // set the owning side to null (unless already changed)
            if ($membership->getPerson() === $this) {
                $membership->setPerson(null);
            }
        }

        return $this;
    }

     /**
     * @var Collection<int, Event>
     */

    public function getEvents(): Collection { return $this->events; }

    public function addEvent(Event $event): static
    {
        if (!$this->events->contains($event)) {
            $this->events->add($event);
            $event->setCreator($this);
        }
        return $this;
    }

    public function removeEvent(Event $event): static
    {
        if ($this->events->removeElement($event)) {
            if ($event->getCreator() === $this) {
                $event->setCreator(null);
            }
        }
        return $this;
    }


     /**
     * @var Collection<int, Chore>
     */

    public function getChores(): Collection { return $this->chores; }

    public function addChore(Chore $chore): static
    {
        if (!$this->chores->contains($chore)) {
            $this->chores->add($chore);
            $chore->setCreator($this);
        }
        return $this;
    }

    public function removeChore(Chore $chore): static
    {
        if ($this->chores->removeElement($chore)) {
            if ($chore->getCreator() === $this) {
                $chore->setCreator(null);
            }
        }
        return $this;
    }

    /**
     * @var Collection<int, ShoppingList>
     */

    public function getShoppingLists(): Collection { return $this->shoppingLists; }

    public function addShoppingList(ShoppingList $list): static
    {
        if (!$this->shoppingLists->contains($list)) {
            $this->shoppingLists->add($list);
            $list->setCreator($this);
        }
        return $this;
    }

    public function removeShoppingList(ShoppingList $list): static
    {
        if ($this->shoppingLists->removeElement($list)) {
            if ($list->getCreator() === $this) {
                $list->setCreator(null);
            }
        }
        return $this;
    }

    /**
     * @var Collection<int, Recipe>
     */

    public function getRecipes(): Collection { return $this->recipes; }

    public function addRecipe(Recipe $recipe): static
    {
        if (!$this->recipes->contains($recipe)) {
            $this->recipes->add($recipe);
            $recipe->setCreator($this);
        }
        return $this;
    }

    public function removeRecipe(Recipe $recipe): static
    {
        if ($this->recipes->removeElement($recipe)) {
            if ($recipe->getCreator() === $this) {
                $recipe->setCreator(null);
            }
        }
        return $this;
    }

    /**
     * @var Collection<int, BudgetGoal>
     */

    public function getBudgetGoals(): Collection { return $this->budgetGoals; }

    public function addBudgetGoal(BudgetGoal $goal): static
    {
        if (!$this->budgetGoals->contains($goal)) {
            $this->budgetGoals->add($goal);
            $goal->setCreator($this);
        }
        return $this;
    }
    public function removeBudgetGoal(BudgetGoal $goal): static
    {
        if ($this->budgetGoals->removeElement($goal)) {
            if ($goal->getCreator() === $this) {
                $goal->setCreator(null);
            }
        }
        return $this;
    }

    /**
     * @var Collection<int, Alert>
     */

    public function getAlerts(): Collection { return $this->alerts; }
    public function addAlert(Alert $alert): static
    {
        if (!$this->alerts->contains($alert)) {
            $this->alerts->add($alert);
            $alert->setMember($this);
        }
        return $this;
    }
    public function removeAlert(Alert $alert): static
    {
        if ($this->alerts->removeElement($alert)) {
            if ($alert->getMember() === $this) {
                $alert->setMember(null);
            }
        }
        return $this;
    }

    /**
     * @var Collection<int, Viewer>
     */

    public function getViewers(): Collection { return $this->viewers; }

    public function addViewer(Viewer $viewer): static
    {
        if (!$this->viewers->contains($viewer)) {
            $this->viewers->add($viewer);
            $viewer->setMember($this);
        }
        return $this;
    }
    public function removeViewer(Viewer $viewer): static
    {
        if ($this->viewers->removeElement($viewer)) {
            if ($viewer->getMember() === $this) {
                $viewer->setMember(null);
            }
        }
        return $this;
    }
}


