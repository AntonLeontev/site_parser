<?php

require_once 'vendor/autoload.php';

use Goutte\Client;
use src\MadeItalyParser;

$client = new Client();
$parser = new MadeItalyParser($client);

try {
    $products = $parser->parseProducts('http://made-italy.ru/shop/dvernye-ruchki-na-rozetke/');

    $json = json_encode($products, JSON_THROW_ON_ERROR | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    file_put_contents('products.json', $json);

    echo 'parsed ' . count($products) . ' products';
} catch (Exception $exception) {
    echo sprintf(
        "Something went wrong:\n%s\n%s : Line %s\nTrace: %s",
        $exception->getMessage(),
        $exception->getFile(),
        $exception->getLine(),
        $exception->getTraceAsString()
    );
}
