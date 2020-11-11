<?php 
/**
 * 
 */
class Parser
{
    private $url;
    
    function __construct(string $url)
    {
        $this->url = $url;
    }

    public function get_content()
    {
        // Настаиваем curl
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $this->url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_FOLLOWLOCATION => true,
        ));

        // Получаем ответ сервера
        $response = curl_exec($curl);
        curl_close($curl);
        return $response;
    }

    public function get_json($data)
    {
        // Получаем из файла контейнер со списком авто
        $pattern = '/<ul class="container container_4">.*?<\/ul>/s';
        preg_match($pattern, $data, $match);

        // Выделяем из каждого элемента название, ссылку на изображение и цену
        $pattern = '/<li.*class="item_name".*<a.*>(.*)<\/a>.*' . 
        'class="item_image".*<img src="(.*)"' .
        '.*class="item_new_price".*<span>(.*)<.*<\/li>/sU';
        preg_match_all($pattern, $match[0], $match, PREG_SET_ORDER);

        // создаем массив для результатов
        $result = [];

        //Заполняем массив результатов, форматируя данные
        foreach ($match as $v) {
            $buf = [];  
            $buf['name']    = trim($v[1]); 
            $buf['picture'] = trim($v[2]); 
            $buf['price']   = str_replace(' ', '', $v[3]); 
            $result[] = $buf;
        }

        // Возвращаем JSON
        return json_encode($result);
    }

    public function write_file($path, $string)
    {
        $f = fopen($path, "w");
        fwrite($f, $string);
    }
}
