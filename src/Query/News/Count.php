<?php

namespace App\Query\News;

use Symfony\Component\Validator\Constraints as Assert;
use App\Query\BaseQuery;

class Count extends BaseQuery
{
    #[Assert\NotBlank(message: 'fromDate.not_blank')]
    #[Assert\Date(message: 'fromDate.invalid_format')]
    public string $fromDate = '';

    #[Assert\NotBlank(message: 'toDate.not_blank')]
    #[Assert\Date(message: 'toDate.invalid_format')]
    public string $toDate = '';
}