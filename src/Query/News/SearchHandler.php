<?php

declare(strict_types=1);

namespace App\Query\News;

use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use App\Repository\NewsRepository;
use App\Entity\News;

class SearchHandler implements MessageHandlerInterface
{
    public function __construct(private NewsRepository $repo) {}

    public function __invoke(Search $message)
    {
        $normalizer = new CamelCaseToSnakeCaseNameConverter();

        $message->orderField = $normalizer->normalize($message->orderField);

        if ($message->idsList && ($message->fromDate || $message->toDate)) {
            $ids = explode(',', $message->idsList);
            $fromDate = $message->fromDate ?? date('Y-m-d', 0);
            $toDate = $message->toDate ?? date('Y-m-d');

            return $this->repo->searchByPeriodAndIds(
                $ids,
                $fromDate,
                $toDate,
                $message->orderField,
                $message->order,
                $message->limit,
                $message->offset,
            );
        } elseif ($message->idsList) {
            $ids = explode(',', $message->idsList);
            
            return $this->repo->searchByIds(
                $ids,
                $message->orderField,
                $message->order,
                $message->limit,
                $message->offset,
            );
        } else {
            $fromDate = $message->fromDate ?? date('Y-m-d', 0);
            $toDate = $message->toDate ?? date('Y-m-d');

            return $this->repo->searchByPeriod(
                $fromDate,
                $toDate,
                $message->orderField,
                $message->order,
                $message->limit,
                $message->offset,
            );
        }
    }
}
