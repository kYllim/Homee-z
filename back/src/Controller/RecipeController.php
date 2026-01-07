<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\ShoppingListRepository;


#[Route('/api/recipes')]
class RecipeController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function index(RecipeRepository $repo): JsonResponse
    {
        $recipes = $repo->findAll();

        $data = array_map(fn (Recipe $recipe) => [
            'id' => $recipe->getId(),
            'title' => $recipe->getTitle(),
            'servings' => $recipe->getServings(),
            'createdAt' => $recipe->getCreatedAt()?->format('Y-m-d H:i:s'),
            'tags' => array_map(
                fn ($rt) => $rt->getTag()->getLabel(),
                $recipe->getRecipeTags()->toArray()
            )
        ], $recipes);

        return $this->json($data);
    }

    #[Route('', methods: ['POST'])]
    public function create(
        Request $request,
        EntityManagerInterface $em,
        ShoppingListRepository $shoppingListRepo
    ): JsonResponse {
        $user = $this->getUser();
        if (!$user) {
            return $this->json(['error' => 'Unauthorized'], 401);
        }

        $person = $user->getPerson();
        if (!$person) {
            return $this->json(['error' => 'Person not found'], 400);
        }

        // ✅ Household récupéré via une ShoppingList existante
        $shoppingList = $shoppingListRepo->findOneBy([
            'creator' => $person
        ]);

        if (!$shoppingList || !$shoppingList->getHousehold()) {
            return $this->json(['error' => 'Household not found'], 400);
        }

        $data = json_decode($request->getContent(), true);

        $recipe = new Recipe();
        $recipe->setTitle($data['title'] ?? 'Nouvelle recette');
        $recipe->setInstructions($data['instructions'] ?? '');
        $recipe->setDescription($data['description'] ?? null);
        $recipe->setServings($data['servings'] ?? null);
        $recipe->setCreatedAt(new \DateTimeImmutable());
        $recipe->setCreator($person);
        $recipe->setHousehold($shoppingList->getHousehold());

        $em->persist($recipe);
        $em->flush();

        return $this->json(['id' => $recipe->getId()], 201);
    }


    #[Route('/{id}', methods: ['GET'])]
    public function show(Recipe $recipe): JsonResponse
    {
        return $this->json([
            'id' => $recipe->getId(),
            'title' => $recipe->getTitle(),
            'instructions' => $recipe->getInstructions(),
            'servings' => $recipe->getServings(),
            'ingredients' => array_map(fn ($i) => [
                'id' => $i->getId(),
                'name' => $i->getName(),
                'quantity' => $i->getQuantity(),
                'unit' => $i->getUnit()
            ], $recipe->getIngredients()->toArray()),
            'tags' => array_map(
                fn ($rt) => $rt->getTag()->getLabel(),
                $recipe->getRecipeTags()->toArray()
            )
        ]);
    }
}
