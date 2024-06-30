<?php

namespace App\Controller;

use App\Entity\Audience;
use App\Entity\ClassType;
use App\Entity\ClassTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Entity\Schedule;

#[Route('/api/v1', name: 'api_')]
class ScheduleController extends AbstractController
{
    #[Route('/schedules', name: 'schedules', methods:['get'])]
    public function index(ManagerRegistry $doctrine): JsonResponse
    {
        return $this->json($doctrine->getRepository(Schedule::class)->findAll());
        
    }
    
        


    #[Route('/schedules', name: 'schedules_create', methods:['post'] )]
    public function create(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        
        $schedule = new Schedule();
        $classType = $doctrine->getRepository(ClassType::class)->find($request->query->getInt(('classType')));
        $classTime = $doctrine->getRepository(ClassTime::class)->find($request->query->getInt(('classTime')));
        $audience = $doctrine->getRepository(Audience::class)->find($request->query->getInt(('audience')));
        $schedule->setDescription($request->query->get('description'));
   	$schedule->setClassType($classType);
   	$schedule->setClassTime($classTime);
   	$schedule->setAudience($audience);
   	$schedule->setDate('11-11-2001');
   	$entityManager->persist($schedule);
        $entityManager->flush();
   
        $data =  [
            'id' => $schedule->getId(),
            'description' => $schedule->getDescription(),
        ];
           
        return $this->json($data);
    }
 
 
    #[Route('/schedules/{id}', name: 'schedule_show', methods:['get'] )]
    public function show(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        $schedule = $doctrine->getRepository(Schedule::class)->find($id);
   
        if (!$schedule) {
   
            return $this->json('No schedule found for id ' . $id, 404);
        }
   
        $data =  [
            'id' => $schedule->getId(),
            'className' => $schedule->getDescription(),
        ];
           
        return $this->json($data);
    }
 
    #[Route('/schedules/{id}', name: 'schedule_update', methods:['put', 'patch'] )]
    public function update(ManagerRegistry $doctrine, Request $request, int $id): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $schedule = $entityManager->getRepository(Schedule::class)->find($id);
   
        if (!$schedule) {
            return $this->json('No schedule found for id' . $id, 404);
        }
   
        $schedule->setDescription($request->query->get('description'));
        $entityManager->flush();
   
        $data =  [
            'id' => $schedule->getId(),
            'description' => $schedule->getDescription(),
        ];
           
        return $this->json($data);
    }
 
    #[Route('/schedules/{id}', name: 'schedule_delete', methods:['delete'] )]
    public function delete(ManagerRegistry $doctrine, int $id): JsonResponse
    {
        $entityManager = $doctrine->getManager();
        $type = $entityManager->getRepository(Schedule::class)->find($id);
   
        if (!$type) {
            return $this->json('No class_type found for id' . $id, 404);
        }
   
        $entityManager->remove($type);
        $entityManager->flush();
   
        return $this->json('Deleted a class_type successfully with id ' . $id);
    }
    #[Route('/schedules/{date}/{id}', name: 'schedule_date', methods:['get'] )]
    public function getByDate(ManagerRegistry $doctrine, string $date, int $id): JsonResponse
    {
        
        $entityManager = $doctrine->getManager();
        $type = $entityManager->getRepository(Schedule::class)->findBy(["date" => $date, "classTime" => $id]);
        return $this->json($type);
    }
}
