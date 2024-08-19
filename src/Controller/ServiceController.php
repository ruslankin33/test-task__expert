<?php

namespace App\Controller;

use App\Repository\ServiceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ServiceController extends AbstractController
{
    #[Route('/services/cost', name: 'get_service_cost', methods: 'GET')]
    public function getServiceCost(Request $request, ServiceRepository $serviceRepository): JsonResponse
    {
        $serviceId = $request->query->get('serviceId');
        $service = $serviceRepository->find($serviceId);

        return new JsonResponse(['cost' => $service ? $service->getCost() : '']);
    }
}
