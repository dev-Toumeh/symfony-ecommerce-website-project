<?php

namespace App\Service;


use App\DTO\DTOInterface;

Interface  ServiceInterface
{
    public function insert(DTOInterface $dto,  $entity = null, array $options = []): mixed;
}