<?php

class Product
{


	// Количество отображаемых товаров по умолчанию
    const SHOW_BY_DEFAULT = 6;

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
        
        // Выполнение комaнды
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

    public static function getRecommendedProducts()
    {
        $db = Db::getConnection();

        $sql = 'SELECT id, name, price, is_new FROM product WHERE status = "1" AND is_recommended = "1" ORDER BY name DESC';

        $result = $db->prepare($sql);

        // Указываем, что хотим получить данные в виде массива
        $result->setFetchMode(PDO::FETCH_ASSOC);
        
        // Выполнение комaнды
        $result->execute();
        
        // Получение и возврат результатов
        $i = 0;
        
        $recProductsList = array();
        
        while ($row = $result->fetch()) {
            $recProductsList[$i]['id'] = $row['id'];
            $recProductsList[$i]['name'] = $row['name'];
            $recProductsList[$i]['price'] = $row['price'];
            $recProductsList[$i]['is_new'] = $row['is_new'];
            $i++;
        
        }
        return $recProductsList;
    }

    public static function getProductsListByCategory($categoryId, $page = 1)
    {
        $limit = Product::SHOW_BY_DEFAULT;
        
        // Смещение (для запроса)
        $offset = ($page - 1) * self::SHOW_BY_DEFAULT;
        
        // Соединение с БД
        $db = Db::getConnection();
        
        // Текст запроса к БД
        $sql = 'SELECT id, name, price, is_new FROM product WHERE status = 1 AND category_id = :category_id ORDER BY id ASC LIMIT :limit OFFSET :offset';
        
        // Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':category_id', $categoryId, PDO::PARAM_INT);
        $result->bindParam(':limit', $limit, PDO::PARAM_INT);
        $result->bindParam(':offset', $offset, PDO::PARAM_INT);
        
        // Выполнение коменды
        $result->execute();
        
        // Получение и возврат результатов
        $i = 0;
        $products = array();
        
        while ($row = $result->fetch()) {
            $products[$i]['id'] = $row['id'];
            $products[$i]['name'] = $row['name'];
            $products[$i]['price'] = $row['price'];
            $products[$i]['is_new'] = $row['is_new'];
            $i++;
        
        }
        return $products;
    }
    

    public static function getProductById($id)
    {
        // Соединение с БД
        $db = Db::getConnection();
        
        // Текст запроса к БД
        $sql = 'SELECT * FROM product WHERE id = :id';
       
        // Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        
        // Указываем, что хотим получить данные в виде массива
        $result->setFetchMode(PDO::FETCH_ASSOC);
        
        // Выполнение коменды
        $result->execute();
        
        // Получение и возврат результатов
        return $result->fetch();
    }

    public static function getTotalProductsInCategory($categoryId)
    {
        // Соединение с БД
        $db = Db::getConnection();
        
        // Текст запроса к БД
        $sql = 'SELECT count(id) AS count FROM product WHERE status="1" AND category_id = :category_id';
        
        // Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':category_id', $categoryId, PDO::PARAM_INT);
        
        // Выполнение коменды
        $result->execute();
        
        // Возвращаем значение count - количество
        $row = $result->fetch();
        
        return $row['count'];
    }

    public static function getProductsByIds($idsArray)
    {
        $products = array();

        $db = Db::getConnection();

        //список id в строке для запроса
        $idsString = implode(',', $idsArray);

        $sql = "SELECT * FROM product WHERE status = '1' AND id IN ($idsString)";

        $result = $db->query($sql);
        $result->setFetchMode(PDO::FETCH_ASSOC);

        $i = 0;

        while ($row = $result->fetch()) {
            $products[$i]['id'] = $row['id'];
            $products[$i]['code'] = $row['code'];
            $products[$i]['name'] = $row['name'];
            $products[$i]['price'] = $row['price'];
            $i++;
        }

        return $products;
    }

    public static function getProductsList()
    {
        $db = Db::getConnection();

        $sql = 'SELECT id, name, code, price FROM product ORDER BY id ASC';

        $result = $db->prepare($sql);

        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        $productsList = array();
        $i = 0;
        while ($row = $result->fetch()) {
            $productsList[$i]['id'] = $row['id'];
            $productsList[$i]['name'] = $row['name'];
            $productsList[$i]['code'] = $row['code'];
            $productsList[$i]['price'] = $row['price'];
            $i++;
        }

        return $productsList;
    }

    public static function deleteProductById($id)
    {
        $db = Db::getConnection();

        $sql = 'DELETE FROM product WHERE id = :id';

        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        return $result->execute();
    }

    public static function createProduct($options)
    {       
        $db = Db::getConnection();

        $sql = 'INSERT INTO product (name, code, price, category_id, brand, availability,'
                . 'description, is_new, is_recommended, status)'
                . 'VALUES '
                . '(:name, :code, :price, :category_id, :brand, :availability,'
                . ':description, :is_new, :is_recommended, :status)';

        $result = $db->prepare($sql);

        $result->bindParam(':name', $options['name'], PDO::PARAM_STR);
        $result->bindParam(':code', $options['code'], PDO::PARAM_STR);
        $result->bindParam(':price', $options['price'], PDO::PARAM_STR);
        $result->bindParam(':category_id', $options['category_id'], PDO::PARAM_INT);
        $result->bindParam(':brand', $options['brand'], PDO::PARAM_STR);
        $result->bindParam(':availability', $options['availability'], PDO::PARAM_INT);
        $result->bindParam(':description', $options['description'], PDO::PARAM_STR);
        $result->bindParam(':is_new', $options['is_new'], PDO::PARAM_INT);
        $result->bindParam(':is_recommended', $options['is_recommended'], PDO::PARAM_INT);
        $result->bindParam(':status', $options['status'], PDO::PARAM_INT);

        // Если запрос выполенен успешно, возвращаем id добавленной записи
        if ($result->execute()) {
            
            return $db->lastInsertId();
        }
        
        // Иначе возвращаем 0
        return 0;

    }   

}