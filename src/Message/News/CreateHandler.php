<?php

declare(strict_types=1);

namespace App\Message\News;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use App\Repository\NewsRepository;
use App\Entity\News;

class CreateHandler implements MessageHandlerInterface
{
    public function __construct(private NewsRepository $repo) {}

    public function __invoke(Create $message)
    {
        $news = new News(
            $message->name,
            $message->body,
            $message->author,
        );

        $this->repo->save($news);

        return $news;
    }
}
