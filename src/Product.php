<?php

namespace src;

class Product
{
    public string $title;
    public string $article;
    public string $preview;

    public function __construct(string $title, string $article, string $preview)
    {
        $this->title = $title;
        $this->article = $article;
        $this->preview = $preview;
    }
}
