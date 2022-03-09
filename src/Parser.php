<?php 

namespace src;

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

class Parser
{
    protected Client $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function getElementsText(Crawler $crawler, string $selector): array
    {
        $text = [];
        $crawler->filter($selector)->each(function ($node) use (&$text) {
            $text[] = $node->text();
        });
        return $text;
    }

    public function getImagesSrc(Crawler $crawler, string $selector): array
    {
        $sources = [];
        $crawler->filter($selector)->each(function (Crawler $node) use (&$sources) {
            $sources[] =  $node->attr('src');
        });
        return $sources;
    }

    public function clickLink(Crawler $crawler, string $value): Crawler
    {
        $link = $crawler->selectLink($value)->link();
        return $this->client->click($link);
    }
}
