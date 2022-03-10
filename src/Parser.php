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

    /**
     * Returns text of all elements by given selector
     *
     * @param Crawler $crawler Crawler instance
     * @param string $selector Css selector
     * @return array Text of each found element
     */
    public function getElementsText(Crawler $crawler, string $selector): array
    {
        $text = [];
        $crawler->filter($selector)->each(function ($node) use (&$text) {
            $text[] = $node->text();
        });
        return $text;
    }

    /**
     * Returns attribute 'src' of all elements by given selector
     *
     * @param Crawler $crawler Crawler instance
     * @param string $selector Css selector
     * @return array Src text of each found element
     */
    public function getImagesSrc(Crawler $crawler, string $selector): array
    {
        $sources = [];
        $crawler->filter($selector)->each(function (Crawler $node) use (&$sources) {
            $sources[] =  $node->attr('src');
        });
        return $sources;
    }

    /**
     * Clicks on link by name
     *
     * @param Crawler $crawler Crawler instance
     * @param string $value Link text
     * @return Crawler Crawler instance of the page by link
     */
    public function clickLink(Crawler $crawler, string $value): Crawler
    {
        $link = $crawler->selectLink($value)->link();
        return $this->client->click($link);
    }
}
