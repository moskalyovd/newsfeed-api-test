<?php

namespace App\Query\News;

use Symfony\Component\Validator\Constraints as Assert;
use App\Query\BaseQuery;

class Search extends BaseQuery
{
    #[Assert\Type(type: 'integer', message: 'limit.invalid_type')]
    public int $limit = 10;

    #[Assert\Type(type: 'integer', message: 'offset.invalid_type')]
    public int $offset = 0;

    #[Assert\Choice(choices: ['createdAt', 'name'], message: 'orderField.not_supported')]
    public string $orderField = 'createdAt';

    #[Assert\Choice(choices: ['ASC', 'DESC'], message: 'order.not_supported')]
    public string $order = 'DESC';

    public ?string $idsList = null;

    #[Assert\Date]
    public ?string $fromDate = null;

    #[Assert\Date]
    public ?string $toDate = null;
}