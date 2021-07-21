<?php

namespace App\Message\News;

use Symfony\Component\Validator\Constraints as Assert;
use App\Message\BaseMessage;
use App\Entity\News;

class Edit extends BaseMessage
{
    #[Assert\NotBlank(message: 'news.not_blank')]
    #[Assert\Type(type: News::class, message: 'news.not_valid')]
    public News $news;

    #[Assert\NotBlank(message: 'name.not_blank')]
    #[Assert\Length(max: 255, maxMessage: 'name.too_long')]
    public string $name;

    #[Assert\NotBlank(message: 'body.not_blank')]
    public string $body;

    #[Assert\NotBlank(message: 'author.not_blank')]
    #[Assert\Length(max: 255, maxMessage: 'author.too_long')]
    public string $author;
}
