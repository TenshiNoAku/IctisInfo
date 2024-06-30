<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\ClassTime;


#[Route('/api/v1', name: 'api_')]
class ClassTimeController extends AbstractController
{
    #[Route('/class_times', name: 'class_time', methods:['get'] )]
    public function index(ManagerRegistry $doctrine): JsonResponse
    {
        return $this->json($doctrine->getRepository(ClassTime::class)->findAll());
    }

    #[Route('/class_times', name: 'class_times_create', methods:['post'] )]
    public function create(ManagerRegistry $doctrine, Request $request): JsonResponse
    {	
    
    
    	if (!$request->query->has('classTime')){
   		return $this->json('parameter classTime is missing', 404);
   	}
        $entityManager = $doctrine->getManager();

        $time = new ClassTime();
        $time->setClassTime($request->query->get('classTime'));
   
        $entityManager->persist($time);
        $entityManager->flush();
   
        $data =  [
            'id' => $time->getId(),
            'classTime' => $time->getClassTime(),
        ];
           
        return $this->json($data);
    }
 
 
    #[Route('/class_times/{id}', name: 'class_time_show', methods:['get'] )]
    public function show(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        $time = $doctrine->getRepository(ClassTime::class)->find($id);
   
        if (!$time) {
   
            return $this->json('No class_time found for id ' . $id, 404);
        }
   
        $data =  [
            'id' => $time->getId(),
            'classTime' => $time->getClassTime(),
        ];
           
        return $this->json($data);
    }
 
    #[Route('/class_times/{id}', name: 'class_time_update', methods:['put', 'patch'] )]
    public function update(ManagerRegistry $doctrine, Request $request, int $id): JsonResponse
    {	
    	
        $entityManager = $doctrine->getManager();
        $time = $entityManager->getRepository(ClassTime::class)->find($id);
   
        if (!$time) {
            return $this->json('No class_time found for id' . $id, 404);
        }
   	
   	if (!$request->query->has('classTime')){
   		return $this->json('parameter classTime is missing', 404);
   	}
   	
        $time->setClassTime($request->query->get('classTime'));
        $entityManager->flush();
   
        $data =  [
            'id' => $time->getId(),
            'classTime' => $time->getClassTime(),
        ];
           
        return $this->json($data);
    }
 
    #[Route('/class_times/{id}', name: 'class_time_delete', methods:['delete'] )]
    public function delete(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $time = $entityManager->getRepository(ClassTime::class)->find($id);
   
        if (!$time) {
            return $this->json('No class_time found for id' . $id, 404);
        }
   
        $entityManager->remove($time);
        $entityManager->flush();
   
        return $this->json('Deleted a class_time successfully with id ' . $id);
    }
}

