<?php

declare(strict_types=1);

namespace App\Message\News;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use App\Repository\NewsRepository;

class DeleteHandler implements MessageHandlerInterface
{
    public function __construct(private NewsRepository $repo) {}

    public function __invoke(Delete $message)
    {
        $this->repo->remove($message->news);

        return $message->news;
    }
}
