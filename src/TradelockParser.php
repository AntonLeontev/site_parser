<?php

namespace src;

use Symfony\Component\DomCrawler\Crawler;

class TradelockParser extends Parser
{
    public function parseProducts(string $url): array
    {
        $products = [];

        $arrContextOptions= [
            'ssl' => [
                'verify_peer'=> false,
                'verify_peer_name'=> false,
            ],
        ];

        $crawler = $this->client->request('GET', $url, $arrContextOptions);
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
