<?php

namespace App\Controller;

use App\Entity\ShoppingList;
use App\Entity\ShoppingItem;
use App\Repository\ShoppingListRepository;
use App\Repository\ShoppingItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Household;
use App\Entity\User;


#[Route('/api/shopping-lists')]
class ShoppingListController extends AbstractController
{

    #[Route('', name: 'shopping_list_index', methods: ['GET'])]
    public function index(ShoppingListRepository $repo): JsonResponse
    {
        $lists = $repo->findAll();

        $data = array_map(function (ShoppingList $list) {
            return [
                'id' => $list->getId(),
                'title' => $list->getTitle(),
                'description' => $list->getDescription(),
                'status' => $list->getStatus(),
                'createdAt' => $list->getCreatedAt()?->format('Y-m-d H:i:s'),
                'items' => array_map(fn($item) => [
                    'id' => $item->getId(),
                    'name' => $item->getName(),
                    'status' => $item->getStatus(),
                ], $list->getShoppingItems()->toArray())
            ];
        }, $lists);

        return $this->json($data);
    }

    #[Route('', name: 'shopping_list_create', methods: ['POST'])]
    public function create(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $list = new ShoppingList();
        $list->setTitle($data['title'] ?? 'Nouvelle liste');
        $list->setDescription($data['description'] ?? null);
        $list->setStatus($data['status'] ?? 'active');
        $list->setCreatedAt(new \DateTimeImmutable());
        if (isset($data['household_id'])) {
            $household = $em->getRepository(Household::class)->find($data['household_id']);
            if ($household) {
                $list->setHousehold($household);
            }
        }
        if (isset($data['creator_id'])) {
            $creator = $em->getRepository(User::class)->find($data['creator_id']);
            if ($creator) {
                $list->setCreator($creator);
            }
        }
        $em->persist($list);
        
        // ✅ AJOUT DES ITEMS
        foreach ($data['items'] ?? [] as $itemData) {
            $item = new ShoppingItem();

            if (is_string($itemData)) {
                $item->setName($itemData);
                $item->setQuantity(1);
                $item->setStatus('to_buy');
            } else {
                $item->setName($itemData['name'] ?? 'Article');
                $item->setQuantity($itemData['quantity'] ?? 1);
                $item->setUnit($itemData['unit'] ?? null);
                $item->setStatus($itemData['status'] ?? 'to_buy');
            }

            $item->setShoppingList($list);
            $em->persist($item);
        }

        $em->flush();

        return $this->json(
            [
                'id' => $list->getId(),
                'title' => $list->getTitle(),
                'itemsCount' => count($list->getShoppingItems())
            ],
            201
        );

        return $this->json(['message' => 'Liste créée avec succès', 'id' => $list->getId()], 201);
    }

    #[Route('/{id}', name: 'shopping_list_show', methods: ['GET'])]
    public function show(ShoppingList $list): JsonResponse
    {
        $items = array_map(function (ShoppingItem $item) {
            return [
                'id' => $item->getId(),
                'name' => $item->getName(),
                'quantity' => $item->getQuantity(),
                'unit' => $item->getUnit(),
                'status' => $item->getStatus()
            ];
        }, $list->getShoppingItems()->toArray());

        return $this->json([
            'id' => $list->getId(),
            'title' => $list->getTitle(),
            'description' => $list->getDescription(),
            'status' => $list->getStatus(),
            'createdAt' => $list->getCreatedAt()?->format('Y-m-d H:i:s'),
            'items' => $items
        ]);
    }

    #[Route('/{id}', name: 'shopping_list_update', methods: ['PATCH'])]
    public function update(Request $request, ShoppingList $list, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (isset($data['title'])) $list->setTitle($data['title']);
        if (isset($data['description'])) $list->setDescription($data['description']);
        if (isset($data['status'])) $list->setStatus($data['status']);

        $em->flush();

        return $this->json(['message' => 'Liste mise à jour avec succès']);
    }

    #[Route('/{id}', name: 'shopping_list_delete', methods: ['DELETE'])]
    public function delete(ShoppingList $list, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($list);
        $em->flush();

        return $this->json(['message' => 'Liste supprimée avec succès']);
    }

    #[Route('/{id}/items', name: 'shopping_list_add_item', methods: ['POST'])]
    public function addItem(Request $request, ShoppingList $list, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $item = new ShoppingItem();
        $item->setName($data['name'] ?? 'Article');
        $item->setQuantity($data['quantity'] ?? 1);
        $item->setUnit($data['unit'] ?? null);
        $item->setStatus($data['status'] ?? 'to_buy');
        $item->setShoppingList($list);

        $em->persist($item);
        $em->flush();

        return $this->json(['message' => 'Article ajouté à la liste', 'itemId' => $item->getId()], 201);
    }

    #[Route('/items/{id}', name: 'shopping_item_delete', methods: ['DELETE'])]
    public function deleteItem(ShoppingItem $item, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($item);
        $em->flush();

        return $this->json(['message' => 'Article supprimé avec succès']);
    }
}
