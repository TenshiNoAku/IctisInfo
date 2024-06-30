<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Audience;


#[Route('/api/v1', name: 'api_')]

class AudienceController extends AbstractController
{
    #[Route('/audiences', name: 'audience', methods:['get'])]
    public function index(ManagerRegistry $doctrine): JsonResponse
    {
        return $this->json($doctrine->getRepository(Audience::class)->findAll());
    }

    #[Route('/audiences', name: 'audience_create', methods:['post'] )]
    public function create(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        $entityManager = $doctrine->getManager();
   
        $audience = new Audience();
        $audience->setName($request->query->get('name'));
   
        $entityManager->persist($audience);
        $entityManager->flush();
   
        $data =  [
            'id' => $audience->getId(),
            'name' => $audience->getName(),
        ];
           
        return $this->json($data);
    }
 
 
    #[Route('/audiences/{id}', name: 'audience_show', methods:['get'] )]
    public function show(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        $audience = $doctrine->getRepository(Audience::class)->find($id);
   
        if (!$audience) {
   
            return $this->json('No audience found for id ' . $id, 404);
        }
   
        $data =  [
            'id' => $audience->getId(),
            'name' => $audience->getName(),
        ];
           
        return $this->json($data);
    }
 
    #[Route('/audiences/{id}', name: 'audience_update', methods:['put', 'patch'] )]
    public function update(ManagerRegistry $doctrine, Request $request, int $id): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $audience = $entityManager->getRepository(Audience::class)->find($id);
   
        if (!$audience) {
            return $this->json('No audience found for id' . $id, 404);
        }
   
        $audience->setName($request->query->get('name'));
        $entityManager->flush();
   
        $data =  [
            'id' => $audience->getId(),
            'name' => $audience->getName(),
        ];
           
        return $this->json($data);
    }
 
    #[Route('/audiences/{id}', name: 'audience_delete', methods:['delete'] )]
    public function delete(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $audience = $entityManager->getRepository(Audience::class)->find($id);
   
        if (!$audience) {
            return $this->json('No audience found for id' . $id, 404);
        }
   
        $entityManager->remove($audience);
        $entityManager->flush();
   
        return $this->json('Deleted a audience successfully with id ' . $id);
    }
       
}
