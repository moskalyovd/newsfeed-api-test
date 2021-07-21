<?php

declare(strict_types=1);

namespace App\Query\News;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use App\Repository\NewsRepository;
use App\Entity\News;

class CountHandler implements MessageHandlerInterface
{
    public function __construct(private NewsRepository $repo) {}

    public function __invoke(Count $message)
    {
        return $this->repo->findCountByPeriod($message->fromDate, $message->toDate);
    }
}
