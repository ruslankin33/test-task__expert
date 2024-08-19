<?php

declare(strict_types=1);

namespace App\Service;

use App\DTO\OrderDTO;
use App\Entity\Order;
use App\Exception\ServiceNotFoundException;
use App\Repository\ServiceRepository;
use Doctrine\ORM\EntityManagerInterface;

readonly class OrderService
{
    public function __construct(
        private ServiceRepository $serviceRepository,
        private EntityManagerInterface $em
    ) {
    }

    /**
     * @throws ServiceNotFoundException
     */
    public function createOrder(OrderDTO $orderDTO): void
    {
        $service = $this->serviceRepository->find($orderDTO->serviceId);
        if (!$service) {
            throw new ServiceNotFoundException();
        }

        $order = new Order();
        $order->setService($service);
        $order->setEmail($orderDTO->email);
        $order->setCreatedAt(new \DateTimeImmutable());

        $this->em->persist($order);
        $this->em->flush();
    }
}