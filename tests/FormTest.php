<?php

namespace App\Tests;

use App\Entity\Order;
use App\Entity\Service;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class FormTest extends WebTestCase
{
    public function testRedirectToLoginPage(): void
    {
        $client = static::createClient();

        $client->request('GET', '/');

        $this->assertResponseRedirects('/login', Response::HTTP_FOUND);
    }

    public function testMainPageIsUp(): void
    {
        $client = $this->getAuthClient();

        $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('title', 'Услуги оценки');
    }

    public function testFormValidation(): void
    {
        $client = $this->getAuthClient();

        $client->request('GET', '/');

        $this->assertResponseStatusCodeSame(200);
        $this->assertSelectorExists('select[name="serviceId"]');
        $this->assertSelectorExists('input[name="email"]');
        $this->assertSelectorExists('input[name="cost"]');
        $this->assertSelectorExists('button[type="submit"]');
    }

    public function testEmailFieldValidation(): void
    {
        $client = $this->getAuthClient();

        $crawler = $client->request('GET', '/');
        $form = $crawler->selectButton('Отправить')->form();
        $form['serviceId'] = '';
        $form['email'] = '';
        $form['cost'] = '';

        $client->submit($form);

        $this->assertSelectorTextContains('body', 'Не переданы обязательные параметры');
    }

    public function testFormSubmission(): void
    {
        $client = $this->getAuthClient();

        $crawler = $client->request('GET', '/');

        $form = $crawler->selectButton('Отправить')->form();

        $form['serviceId'] = 1;
        $form['email'] = 'user@test.ru';

        $client->submit($form);

        $em = $client->getContainer()->get('doctrine')->getManager();
        $service = $em->getRepository(Service::class)->find(1);
        $order = $em->getRepository(Order::class)->findOneBy([
            'email' => 'user@test.ru',
            'service' => $service,
        ]);

        $this->assertEquals(1, $order->getService()->getId());
        $this->assertEquals('user@test.ru', $order->getEmail());
    }

    private function getAuthClient(): KernelBrowser
    {
        $client = static::createClient();
        $client->request('GET', '/login');
        $client->submitForm('Войти', [
            '_username' => 'user',
            '_password' => 'user'
        ]);

        return $client;
    }
}
