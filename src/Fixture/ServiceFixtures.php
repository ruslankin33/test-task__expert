<?php

namespace App\Fixture;

use App\Entity\Service;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class ServiceFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $fakeData = [
            ['name' => 'Оценка стоимости автомобиля', 'cost' => 500],
            ['name' => 'Оценка стоимости квартиры', 'cost' => 1000],
            ['name' => 'Оценка стоимости бизнеса', 'cost' => 1500],
        ];

        foreach ($fakeData as $fakeService) {
            $service = new Service();
            $service->setName($fakeService["name"]);
            $service->setCost($fakeService["cost"]);

            $manager->persist($service);
        }
        $manager->flush();
    }
}
