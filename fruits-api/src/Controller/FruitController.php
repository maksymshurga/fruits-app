<?php

namespace App\Controller;

use App\Entity\Fruit;
use App\Repository\FruitRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class FruitController
{
    private $fruitRepository;

    public function __construct(FruitRepository $fruitRepository)
    {
        $this->fruitRepository = $fruitRepository;
    }

    /**
     * @Route("/fruits")
     */
    public function index(Request $request): JsonResponse
    {
        $page = intval($request->query->get('page', 1));
        $limit = intval($request->query->get('limit', 10));
        $name = $request->query->get('name', '');
        $family = $request->query->get('family', '');

        $result = $this->fruitRepository
            ->findByNameAndFamily($name, $family, $page, $limit);

        return new JsonResponse($result);
    }

    /**
     * @Route("/fruits/{id}", methods={"PUT"})
     */
    public function updateFavorite(int $id, Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        $favoriteFruits = $this->fruitRepository->findFavorites();
        if (count($favoriteFruits) >= 10 && $requestData['favorite']) {
            return new JsonResponse([
                'result' => false
            ]);
        }

        $result = $this->fruitRepository->updateFavorite($id, $requestData['favorite']);

        return new JsonResponse([
            'result' => (bool)$result
        ]);
    }
    
    /**
     * @Route("/fruits/favorites")
     */
    public function favorites(): JsonResponse
    {
        $result = $this->fruitRepository->findFavorites();

        return new JsonResponse($result);
    }
}
