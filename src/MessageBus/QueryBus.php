<?php

declare(strict_types=1);

namespace App\MessageBus;

use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;
use App\Query\BaseQuery;

class QueryBus
{
    use HandleTrait;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    public function query(BaseQuery $query): mixed
    {
        return $this->handle($query);
    }
}