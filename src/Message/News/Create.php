<?php

namespace App\Message\News;

use Symfony\Component\Validator\Constraints as Assert;
use App\Message\BaseMessage;

class Create extends BaseMessage
{
    #[Assert\NotBlank(message: 'name.not_blank')]
    #[Assert\Length(max: 255, maxMessage: 'name.too_long')]
    public string $name;

    #[Assert\NotBlank(message: 'body.not_blank')]
    public string $body;

    #[Assert\NotBlank(message: 'author.not_blank')]
    #[Assert\Length(max: 255, maxMessage: 'author.too_long')]
    public string $author;
}
