<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Patch;
use App\State\EventProcessor;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    // Définition des groupes de sérialisation pour GET et POST/PATCH
    normalizationContext: ['groups' => ['event:read']],
    denormalizationContext: ['groups' => ['event:write']],
    operations: [
        new GetCollection(),  // Récupérer tous les événements
        new Get(),            // Récupérer un événement spécifique
        new Post(processor: EventProcessor::class), // Création avec logique du EventProcessor
        new Patch(security: "object.getCreator() == user"), // Modification réservée au créateur
        new Delete(security: "object.getCreator() == user"), // Suppression réservée au créateur
    ]
)]
#[ORM\HasLifecycleCallbacks]
#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    // Identifiant unique de l'événement
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['event:read'])]
    private ?int $id = null;

    // Titre de l'événement
    #[ORM\Column(length: 255)]
    #[Groups(['event:read', 'event:write'])]
    private ?string $title = null;

    // Description détaillée
    #[ORM\Column(type: Types::TEXT)]
    #[Groups(['event:read', 'event:write'])]
    private ?string $description = null;

    // Date de création automatique
    #[ORM\Column(type: 'datetime_immutable')]
    #[Groups(['event:read'])]
    private ?\DateTimeImmutable $createdAt = null;

    // Date et heure de début de l'événement
    #[ORM\Column(type: 'datetime')]
    #[Groups(['event:read', 'event:write'])]
    private ?\DateTimeInterface $startAt = null;

    // Date et heure de fin
    #[ORM\Column(type: 'datetime')]
    #[Groups(['event:read', 'event:write'])]
    private ?\DateTimeInterface $endAt = null;

    // Type (ex: tâche, réunion...)
    #[ORM\Column(length: 255)]
    #[Groups(['event:read', 'event:write'])]
    private ?string $type = null;

    // Statut automatisé ou renseigné manuellement (à faire / en cours / terminé)
    #[ORM\Column(length: 255)]
    #[Groups(['event:read', 'event:write'])]
    private ?string $status = null;

    // Le foyer associé à l'événement
    #[ORM\ManyToOne(inversedBy: 'events')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['event:read', 'event:write'])]
    private ?Household $household = null;

    // L'utilisateur créateur de l'événement
    #[ORM\ManyToOne(inversedBy: 'events')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['event:read', 'event:write'])]
    private ?User $creator = null;

    /**
     * Liste des alertes liées à cet événement
     * @var Collection<int, Alert>
     */
    #[ORM\OneToMany(targetEntity: Alert::class, mappedBy: 'event')]
    #[Groups(['event:read'])]
    private Collection $alerts;

    public function __construct()
    {
        $this->alerts = new ArrayCollection();
    }


    public function getId(): ?int { return $this->id; }

    public function getTitle(): ?string { return $this->title; }
    public function setTitle(string $title): static { $this->title = $title; return $this; }

    public function getDescription(): ?string { return $this->description; }
    public function setDescription(string $description): static { $this->description = $description; return $this; }

    public function getCreatedAt(): ?\DateTimeImmutable { return $this->createdAt; }
    public function setCreatedAt(\DateTimeImmutable $createdAt): static { $this->createdAt = $createdAt; return $this; }

    public function getStartAt(): ?\DateTimeInterface { return $this->startAt; }
    public function setStartAt(\DateTimeInterface $startAt): static { $this->startAt = $startAt; return $this; }

    public function getEndAt(): ?\DateTimeInterface { return $this->endAt; }
    public function setEndAt(\DateTimeInterface $endAt): static { $this->endAt = $endAt; return $this; }

    public function getType(): ?string { return $this->type; }
    public function setType(string $type): static { $this->type = $type; return $this; }

    public function getStatus(): ?string { return $this->status; }
    public function setStatus(string $status): static { $this->status = $status; return $this; }

    public function getHousehold(): ?Household { return $this->household; }
    public function setHousehold(?Household $household): static { $this->household = $household; return $this; }

    public function getCreator(): ?User { return $this->creator; }
    public function setCreator(?User $creator): static { $this->creator = $creator; return $this; }

    /**
     * @return Collection<int, Alert>
     */
    public function getAlerts(): Collection { return $this->alerts; }

    public function addAlert(Alert $alert): static
    {
        if (!$this->alerts->contains($alert)) {
            $this->alerts->add($alert);
            $alert->setEvent($this);
        }
        return $this;
    }

    public function removeAlert(Alert $alert): static
    {
        if ($this->alerts->removeElement($alert) && $alert->getEvent() === $this) {
            $alert->setEvent(null);
        }
        return $this;
    }

    #[ORM\PrePersist]
    public function onPrePersist(): void
    {
        // Si créé manuellement sans date de création, on initialise automatiquement
        if (!$this->createdAt) {
            $this->createdAt = new \DateTimeImmutable();
        }
    }
}
