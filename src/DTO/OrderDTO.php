<?php

declare(strict_types=1);

namespace App\DTO;

use Symfony\Component\Validator\Constraints as Assert;

readonly class OrderDTO
{
    public function __construct(
        #[Assert\NotBlank(message: 'Не передан обязательный параметр: Email')]
        #[Assert\Email(message: 'Некорректный email')]
        public string $email,
        #[Assert\NotBlank(message: 'Не передан обязательный параметр: ИД услуги')]
        public int $serviceId,
    ) {}
}
