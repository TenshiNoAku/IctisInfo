<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ScheduleController extends AbstractController
{   
    
    #[Route('api/schedule', name: 'app_schedule')]
    public function index(): JsonResponse
    {   

        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ScheduleController.php',
        ]);
    }
}
