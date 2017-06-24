<?php


class Category
{

	public static function getCategoriesList()
	{
		$db = Db::getConnection();

		$categoryList = array();

		$result = $db->query('SELECT id, name FROM category ORDER BY sort_order ASC');

		$i = 0;
		while ($row = $result->fetch()) {
			$categoryList[$i]['id'] = $row['id'];
			$categoryList[$i]['name'] = $row['name'];
			$i++;
		}

		return $categoryList;
	}

	/**
     * Возвращает массив категорий для списка в админпанели <br/>
     * (при этом в результат попадают и включенные и выключенные категории)
     * @return array <p>Массив категорий</p>
     */
    public static function getCategoriesListAdmin()
    {
        // Соединение с БД
        $db = Db::getConnection();

        // Запрос к БД
        $result = $db->query('SELECT id, name, sort_order, status FROM category ORDER BY sort_order ASC');
        
        // Получение и возврат результатов
        $categoryList = array();
        
        $i = 0;
        
        while ($row = $result->fetch()) {
            $categoryList[$i]['id'] = $row['id'];
            $categoryList[$i]['name'] = $row['name'];
            $categoryList[$i]['sort_order'] = $row['sort_order'];
            $categoryList[$i]['status'] = $row['status'];
            $i++;
        }
        
        return $categoryList;
    }

    public static function createCategory($name, $sort_order, $status)
    {
        // Соединение с БД
        $db = Db::getConnection();

        // Запрос к БД
        $sql = 'INSERT INTO category (name, sort_order, status) VALUES (:name, :sort_order, :status)';
        
        $result = $db->prepare($sql);

        $result->bindParam(':name', $name, PDO::PARAM_STR);
        $result->bindParam(':sort_order', $sort_order, PDO::PARAM_INT);
        $result->bindParam(':status', $status, PDO::PARAM_INT);

        return $result->execute();
    }

    public static function getStatusText($status)
    {
        switch ($status) {
            case '1':
            return 'Отображается';
            break;
            case '0':
            return 'Скрыта';
            break;
        }
    }
}