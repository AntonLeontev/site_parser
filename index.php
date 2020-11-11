<?php

require_once 'Parser.php';

$parser = new Parser("https://olimp-cars.ru/auto");
$site_page = $parser->get_content();
$json = $parser->get_json($site_page);
$parser->write_file("parse_data.json", $json);
