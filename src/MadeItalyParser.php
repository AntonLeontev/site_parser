<?php

namespace src;

use Symfony\Component\DomCrawler\Crawler;

/**
 * Parser for shop pages of http://made-italy.ru
 */
class MadeItalyParser extends Parser
{
    /**
     * Collects title, article and preview link of each product
     * from all pages by base uri
     *
     * Use do-while because there are no pagination on some pages
     * While works incorrect on them
     *
     * @param string $baseUri Uri without parameters
     * @return array Array of Products objects
     */
    public function parseProducts(string $baseUri): array
    {
        $products = [];
        $pageNumber = 1;
        $lastPageNumber =  $this->getLastPageNumber($baseUri);

        do {
            $this->parseProductsOnPage($baseUri, $pageNumber, $products);
            $pageNumber++;
        } while ($pageNumber <= $lastPageNumber);

        return $products;
    }

    /**
     * Parses products from current page
     *
     * @param string $baseUri Base uri
     * @param int $pageNumber Current page number
     * @param array $products Store for products
     */
    private function parseProductsOnPage(string $baseUri, $pageNumber, &$products): void
    {
        $currentPageUri = sprintf('%spage-%s/#productsbox', $baseUri, $pageNumber);
        $crawler = $this->client->request('GET', $currentPageUri);
        $productsContainer = $crawler->filter('div.main-content div div.entry-container');

        $productsContainer->each(static function (Crawler $node) use (&$products) {
            $title = $node->filter('a.title')->text();
            $article = $node->filter('div.description div div.right')->text();
            $image = $node->filter('div.photo-container a img');
            $preview = ($image->count() > 0) ? $image->attr('src') : '';
            $products[] = new Product($title, $article, $preview);
        });
    }

    /**
     * Finds last page in pagination
     *
     * @param string $uri Uri to parse
     * @return int Page number
     */
    private function getLastPageNumber(string $uri): int
    {
        $crawler = $this->client->request('GET', $uri);
        $pagination = $this->getElementsText($crawler, '.paginator a');
        if (!empty($pagination)) {
            return (int) end($pagination);
        }
        return 0;
    }
}
