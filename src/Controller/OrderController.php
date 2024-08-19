<?php

namespace App\Controller;

use App\DTO\OrderDTO;
use App\Exception\ServiceNotFoundException;
use App\Service\OrderService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class OrderController extends AbstractController
{

    public function __construct(
        private readonly OrderService $orderService,
        private readonly ValidatorInterface $validator
    ) {
    }

    #[Route(name: 'create_order', methods: 'POST')]
    public function createOrderAction(Request $request): Response
    {
        $params = $request->request->all();
        if (!isset($params['email']) || !isset($params['serviceId'])) {
            return $this->render('error.html.twig', [
                'text' => 'Не переданы обязательные параметры',
            ]);
        }

        try {
            $orderDTO = new OrderDTO(
                email: $params['email'],
                serviceId: $params['serviceId'],
            );
            $errors = $this->validator->validate($orderDTO);
            if (count($errors) > 0) {
                return $this->render('validation.html.twig', [
                    'errors' => $errors,
                ]);
            }
            $this->orderService->createOrder($orderDTO);
        } catch (ServiceNotFoundException $e) {
            return $this->render('error.html.twig', [
                'text' => 'Не найдена переданная услуга',
            ]);
        } catch (\Exception $e) {
            return $this->render('error.html.twig', [
                'text' => $e->getMessage(),
            ]);
        }

        return new Response("
            <script>
                alert('Заказ успешно создан!');
                window.location.href = '" . $this->generateUrl('main_page') . "';
            </script>
        ");
    }
}
