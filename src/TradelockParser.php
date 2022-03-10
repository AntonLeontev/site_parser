<?php

namespace src;

use Symfony\Component\DomCrawler\Crawler;

/**
 * Parser for shop pages of https://tlock.ru
 */
class TradelockParser extends Parser
{
    /**
     * Collects title, article and preview link of each product
     * from all pages by base uri
     *
     * @param string $uri Uri to parse
     * @return array Array of Products objects
     */
    public function parseProducts(string $uri): array
    {
        $products = [];

        $crawler = $this->client->request('GET', $uri);
        $goods = $crawler->filter('div.fgrid__i clearfix div.fgrid__item');

        $goods->each(static function (Crawler $node) use (&$products) {
            $title = $node->filter('a div.prod__txt div.prod__name')->text();
            $article = $node->filter('a div.prod__id')->text();
            $image = $node->filter('a div.prod__pic img');
            $preview = ($image->count() > 0) ? $image->attr('src') : '';
            $products[] = new Product($title, $article, $preview);
        });

        return $products;
    }
}
