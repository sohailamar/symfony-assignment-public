<?php

namespace App\Controller;

use App\Entity\Fruits;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

class FruitController extends AbstractController
{

    #[Route('/fruits', name: 'app_fruit', methods:['get'])]
    public function index(ManagerRegistry $doctrine,Request $request): JsonResponse
    {
        $name = $request->query->get('name', "");
        $family = $request->query->get('family', "");
        $fruits = $doctrine->getRepository(Fruits::class)
            ->searchByNameAndFamily($name, $family);

        return $this->json($fruits);
    }

    #[Route('/fruits/{id}', name: 'app_fruit_patch', methods:['patch'])]
    public function update(int $id,ManagerRegistry $doctrine,Request $request): JsonResponse
    {
        // $id = $request->query->get('id');
        $favourite = (bool) $request->query->get('favourite');
        $fruit = $doctrine->getRepository(Fruits::class)->find($id);
        if ($fruit) {
            $fruit->setFavourite($favourite);
            $doctrine->getRepository(Fruits::class)->save($fruit,true);
        }
        
        return $this->json(["succes" => "kO"]);
    }

    #[Route('/favourite_fruits', name: 'app_fruit_favourite',methods:['get'])]
    public function favourites(ManagerRegistry $doctrine,Request $request): JsonResponse
    {
        $fruits = $doctrine->getRepository(Fruits::class)->searchFavourites();

        return $this->json($fruits);
    }
}
