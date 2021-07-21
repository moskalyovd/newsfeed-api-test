<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Messenger\Stamp\HandledStamp;
use Symfony\Component\Messenger\Bridge\Amqp\Transport\AmqpStamp;
use Symfony\Component\Serializer\SerializerInterface;
use App\MessageBus\QueryBus;
use App\Entity\News;
use App\Message\News\Create as CreateNewsMessage;
use App\Message\News\Edit as EditNewsMessage;
use App\Message\News\Delete as DeleteNewsMessage;
use App\Query\News\Search as SearchQuery;
use App\Query\News\Count as CountQuery;
use App\Form\Type\NewsType;
use App\Repository\NewsRepository;

class NewsController extends AbstractController
{
    public function __construct(private NewsRepository $repo, private SerializerInterface $serializer, private QueryBus $queryBus)
    {
    }

    #[Route('/news', methods: ['POST'], name: 'app_news_create')]
    public function create(Request $request): Response
    {
        // сообщения этого типа обрабатываются асинхронно (сообщения ходят через rabbitmq)
        $message = new CreateNewsMessage($request->request->all());
                 
        $this->dispatchMessage($message);

        return new JsonResponse(null, 201, [
            'Location' => $this->generateUrl('app_news_get', ['id' => 1])
        ]);
    }

    #[Route('/news/{id}', methods: ['PUT'], name: 'app_news_edit')]
    public function edit(News $news, Request $request): Response
    {
        $message = new EditNewsMessage($request->request->all());
        $message->news = $news;

        $this->dispatchMessage($message);

        return new JsonResponse([
            'result' => 'ok',
        ]);
    }

    #[Route('/news/{id}', methods: ['DELETE'], name: 'app_news_delete')]
    public function delete(News $news, Request $request): Response
    {
        $message = new DeleteNewsMessage(['news' => $news]);

        $this->dispatchMessage($message);

        return new JsonResponse(null, 204);
    }

    #[Route('/news/{id}', methods: ['GET'], name: 'app_news_get')]
    public function getOne(News $news, Request $request): Response
    {
        return new JsonResponse([
            'result' => 'ok',
            'data' => $this->serializer->normalize($news)
        ]);
    }

    #[Route('/news', methods: ['GET'], name: 'app_newsfeed_get')]
    public function getNewsfeed(Request $request): Response
    {
        $query = new SearchQuery($request->query->all());

        $newsList = $this->queryBus->query($query);

        return new JsonResponse([
            'result' => 'ok',
            'data' => $this->serializer->normalize($newsList)
        ]);
    }

    #[Route('/news/count', methods: ['GET'], name: 'app_news_count_by_period', priority: 1)]
    public function getNewsfeedCountByPeriod(Request $request): Response
    {
        $query = new CountQuery($request->query->all());

        $data = $this->queryBus->query($query);

        return new JsonResponse([
            'result' => 'ok',
            'data' => $data
        ]);
    }
}