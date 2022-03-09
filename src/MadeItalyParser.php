<?php

namespace src;

use Symfony\Component\DomCrawler\Crawler;

class MadeItalyParser extends Parser
{
    public function parseProducts(string $baseUrl): array
    {
        $products = [];
        $pageNumber = 1;
        $lastPageNumber =  $this->getLastPageNumber($baseUrl);

        do {
            $url = sprintf('%spage-%s/#productsbox', $baseUrl, $pageNumber);
            $crawler = $this->client->request('GET', $url);
            $goods = $crawler->filter('div.main-content div div.entry-container');

            $goods->each(static function (Crawler $node) use (&$products) {
                $title = $node->filter('a.title')->text();
                $article = $node->filter('div.description div div.right')->text();
                $image = $node->filter('div.photo-container a img');
                $preview = ($image->count() > 0) ? $image->attr('src') : '';
                $products[] = new Product($title, $article, $preview);
            });

            $pageNumber++;
        } while ($pageNumber <= $lastPageNumber);

        return $products;
    }

    private function getLastPageNumber(string $url): int
    {
        $crawler = $this->client->request('GET', $url);
        $pagination = $this->getElementsText($crawler, '.paginator a');
        return (int)end($pagination);
    }
}
