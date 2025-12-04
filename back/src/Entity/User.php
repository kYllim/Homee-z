<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use Symfony\Component\Validator\Constraints as Assert;

#[ApiResource]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`users`')]
class User implements UserInterface,PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le prénom est obligatoire")]
    private ?string $firstName = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Le nom de famille est obligatoire")]
    private ?string $lastName = null;

    #[ORM\Column(length: 255, nullable: true, unique: true)]
    #[Assert\NotBlank(message: "L'email est obligatoire")]
    #[Assert\Email(message: "L'email n'est pas valide")]
    private ?string $email = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\NotBlank(message: "Le mot de passe est obligatoire")]
    private ?string $password = null;

    #[ORM\Column(type: 'json')]
    private array $roles = [];

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    /**
     * @var Collection<int, UserHousehold>
     */
    #[ORM\ManyToMany(targetEntity: UserHousehold::class, mappedBy: 'member')]
    private Collection $userHouseholds;

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

    #[ORM\Column]
    private ?bool $Isverified = false;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $VerificationToken = null;

    public function __construct()
    {
        $this->userHouseholds = new ArrayCollection();
        $this->events = new ArrayCollection();
        $this->chores = new ArrayCollection();
        $this->shoppingLists = new ArrayCollection();
        $this->recipes = new ArrayCollection();
        $this->budgetGoals = new ArrayCollection();
        $this->alerts = new ArrayCollection();
        $this->viewers = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    public function getRoles(): array
    {
        $roles = $this->roles;
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    public function eraseCredentials(): void
    {

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): static
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): static
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): static
    {
        $this->password = $password;

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
     * @return Collection<int, UserHousehold>
     */
    public function getUserHouseholds(): Collection
    {
        return $this->userHouseholds;
    }

    public function addUserHousehold(UserHousehold $userHousehold): static
    {
        if (!$this->userHouseholds->contains($userHousehold)) {
            $this->userHouseholds->add($userHousehold);
            $userHousehold->addMember($this);
        }

        return $this;
    }

    public function removeUserHousehold(UserHousehold $userHousehold): static
    {
        if ($this->userHouseholds->removeElement($userHousehold)) {
            $userHousehold->removeMember($this);
        }

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
            $event->setCreator($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): static
    {
        if ($this->events->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getCreator() === $this) {
                $event->setCreator(null);
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
            $chore->setCreator($this);
        }

        return $this;
    }

    public function removeChore(Chore $chore): static
    {
        if ($this->chores->removeElement($chore)) {
            // set the owning side to null (unless already changed)
            if ($chore->getCreator() === $this) {
                $chore->setCreator(null);
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
            $shoppingList->setCreator($this);
        }

        return $this;
    }

    public function removeShoppingList(ShoppingList $shoppingList): static
    {
        if ($this->shoppingLists->removeElement($shoppingList)) {
            // set the owning side to null (unless already changed)
            if ($shoppingList->getCreator() === $this) {
                $shoppingList->setCreator(null);
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
            $recipe->setCreator($this);
        }

        return $this;
    }

    public function removeRecipe(Recipe $recipe): static
    {
        if ($this->recipes->removeElement($recipe)) {
            // set the owning side to null (unless already changed)
            if ($recipe->getCreator() === $this) {
                $recipe->setCreator(null);
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
            $budgetGoal->setCreator($this);
        }

        return $this;
    }

    public function removeBudgetGoal(BudgetGoal $budgetGoal): static
    {
        if ($this->budgetGoals->removeElement($budgetGoal)) {
            // set the owning side to null (unless already changed)
            if ($budgetGoal->getCreator() === $this) {
                $budgetGoal->setCreator(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Alert>
     */
    public function getAlerts(): Collection
    {
        return $this->alerts;
    }

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
            // set the owning side to null (unless already changed)
            if ($alert->getMember() === $this) {
                $alert->setMember(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Viewer>
     */
    public function getViewers(): Collection
    {
        return $this->viewers;
    }

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
            // set the owning side to null (unless already changed)
            if ($viewer->getMember() === $this) {
                $viewer->setMember(null);
            }
        }

        return $this;
    }

    public function isverified(): ?bool
    {
        return $this->Isverified;
    }

    public function setIsverified(bool $Isverified): static
    {
        $this->Isverified = $Isverified;

        return $this;
    }

    public function getVerificationToken(): ?string
    {
        return $this->VerificationToken;
    }

    public function setVerificationToken(?string $VerificationToken): static
    {
        $this->VerificationToken = $VerificationToken;

        return $this;
    }


}
