<?php

class Product
{


	// Количество отображаемых товаров по умолчанию
    const SHOW_BY_DEFAULT = 6;

    /**
     * Возвращает массив последних товаров
     * @param type $count [optional] <p>Количество</p>
     * @param type $page [optional] <p>Номер текущей страницы</p>
     * @return array <p>Массив с товарами</p>
     */
    public static function getLatestProducts($count = self::SHOW_BY_DEFAULT)
    {
        
        // Соединение с БД
        $db = Db::getConnection();
        
        // Текст запроса к БД
        $sql = 'SELECT id, name, price, is_new FROM product WHERE status = "1" ORDER BY id DESC LIMIT :count';
        
        // Используется подготовленный запрос
        $result = $db->prepare($sql);
        
        $result->bindParam(':count', $count, PDO::PARAM_INT);
        
        // Указываем, что хотим получить данные в виде массива
        $result->setFetchMode(PDO::FETCH_ASSOC);
        
        // Выполнение коменды
        $result->execute();
        
        // Получение и возврат результатов
        $i = 0;
        
        $productsList = array();
        
        while ($row = $result->fetch()) {
            $productsList[$i]['id'] = $row['id'];
            $productsList[$i]['name'] = $row['name'];
            $productsList[$i]['price'] = $row['price'];
            $productsList[$i]['is_new'] = $row['is_new'];
            $i++;
        
        }
        return $productsList;
    }
}