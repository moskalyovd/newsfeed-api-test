<?php

namespace App\Message\News;

use Symfony\Component\Validator\Constraints as Assert;
use App\Message\BaseMessage;
use App\Entity\News;

class Delete extends BaseMessage
{
    #[Assert\NotBlank(message: 'news.not_blank')]
    #[Assert\Type(type: News::class, message: 'news.not_valid')]
    public News $news;
}
