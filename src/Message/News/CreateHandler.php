<?php

declare(strict_types=1);

namespace App\Message\News;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use App\Repository\NewsRepository;
use App\Entity\News;

class CreateHandler implements MessageHandlerInterface
{
    public function __construct(private NewsRepository $repo, private SluggerInterface $slugger) {}

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
