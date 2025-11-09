<?php

namespace App\Entity;

use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TagRepository::class)]
class Tag
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $label = null;

    #[ORM\Column(length: 255)]
    private ?string $category = null;

    /**
     * @var Collection<int, RecipeTag>
     */
    #[ORM\OneToMany(targetEntity: RecipeTag::class, mappedBy: 'tag')]
    private Collection $recipeTags;

    public function __construct()
    {
        $this->recipeTags = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): static
    {
        $this->label = $label;

        return $this;
    }

    public function getCategory(): ?string
    {
        return $this->category;
    }

    public function setCategory(string $category): static
    {
        $this->category = $category;

        return $this;
    }

    /**
     * @return Collection<int, RecipeTag>
     */
    public function getRecipeTags(): Collection
    {
        return $this->recipeTags;
    }

    public function addRecipeTag(RecipeTag $recipeTag): static
    {
        if (!$this->recipeTags->contains($recipeTag)) {
            $this->recipeTags->add($recipeTag);
            $recipeTag->setTag($this);
        }

        return $this;
    }

    public function removeRecipeTag(RecipeTag $recipeTag): static
    {
        if ($this->recipeTags->removeElement($recipeTag)) {
            // set the owning side to null (unless already changed)
            if ($recipeTag->getTag() === $this) {
                $recipeTag->setTag(null);
            }
        }

        return $this;
    }
}
