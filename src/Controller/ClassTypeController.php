<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\ClassType;


#[Route('/api/v1', name: 'api_')]
class ClassTypeController extends AbstractController
{
    #[Route('/class_types', name: 'class_types', methods:['get'])]
    public function index(ManagerRegistry $doctrine): JsonResponse
    {
        return $this->json($doctrine->getRepository(ClassType::class)->findAll());
    }

    #[Route('/class_types', name: 'class_types_create', methods:['post'] )]
    public function create(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        $entityManager = $doctrine->getManager();
   
        $type = new ClassType();
        $type->setClassName($request->query->get('className'));
   
        $entityManager->persist($type);
        $entityManager->flush();
   
        $data =  [
            'id' => $type->getId(),
            'className' => $type->getClassName(),
        ];
           
        return $this->json($data);
    }
 
 
    #[Route('/class_types/{id}', name: 'class_type_show', methods:['get'] )]
    public function show(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        $type = $doctrine->getRepository(ClassType::class)->find($id);
   
        if (!$type) {
   
            return $this->json('No class_type found for id ' . $id, 404);
        }
   
        $data =  [
            'id' => $type->getId(),
            'className' => $type->getClassName(),
        ];
           
        return $this->json($data);
    }
 
    #[Route('/class_types/{id}', name: 'class_type_update', methods:['put', 'patch'] )]
    public function update(ManagerRegistry $doctrine, Request $request, int $id): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $type = $entityManager->getRepository(ClassType::class)->find($id);
   
        if (!$type) {
            return $this->json('No class_type found for id' . $id, 404);
        }
   
        $type->setClassName($request->query->get('className'));
        $entityManager->flush();
   
        $data =  [
            'id' => $type->getId(),
            'className' => $type->getClassName(),
        ];
           
        return $this->json($data);
    }
 
    #[Route('/class_types/{id}', name: 'class_type_delete', methods:['delete'] )]
    public function delete(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $type = $entityManager->getRepository(ClassType::class)->find($id);
   
        if (!$type) {
            return $this->json('No class_type found for id' . $id, 404);
        }
   
        $entityManager->remove($type);
        $entityManager->flush();
   
        return $this->json('Deleted a class_type successfully with id ' . $id);
    }
}

