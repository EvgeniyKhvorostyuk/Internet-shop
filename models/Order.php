<?php 



class Order
{
	public static function save($userName, $userPhone, $userComment, $userId, $products)
	{
		$db = Db::getConnection();

		$sql = 'INSERT INTO orders (user_id, user_name, user_phone, user_comment, products) VALUES (:userId, :userName, :userPhone, :userComment, :products)';

		$products = json_encode($products);

		$result = $db->prepare($sql);
		$result->bindParam(':userName', $userName, PDO::PARAM_STR);
        $result->bindParam(':userPhone', $userPhone, PDO::PARAM_STR);
        $result->bindParam(':userComment', $userComment, PDO::PARAM_STR);
        $result->bindParam(':userId', $userId, PDO::PARAM_STR);
        $result->bindParam(':products', $products, PDO::PARAM_STR);

        return $result->execute();
	}

	public static function getOrderListAdmin()
	{
		$db = Db::getConnection();

		$sql = 'SELECT id, user_name, user_phone, date, status FROM orders ORDER BY id ASC';

		$result = $db->prepare($sql);

		$result->setFetchMode(PDO::FETCH_ASSOC);

		$result->execute();

		$ordersList = array();

		$i = 0;

		while ($row = $result->fetch()) {
			$ordersList[$i]['id'] = $row['id'];
			$ordersList[$i]['user_name'] = $row['user_name'];
			$ordersList[$i]['user_phone'] = $row['user_phone'];
			$ordersList[$i]['date'] = $row['date'];
			$ordersList[$i]['status'] = $row['status'];
			$i ++;
		}

		return $ordersList;
	}

	public static function getStatusText($status)
	{
		switch ($status) {
			case '1':
			return 'Новый заказ';
			break;
			case '2':
			return 'В обработке';
			break;
			case '3':
			return 'Доставляется';
			break;
			case '4':
			return 'Закрыт';
			break;
		}
	}

	public static function getOrderById($id)
    {
        
        $db = Db::getConnection();
        

        $sql = 'SELECT * FROM orders WHERE id = :id';
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        
        // Указываем, что хотим получить данные в виде массива
        $result->setFetchMode(PDO::FETCH_ASSOC);
        

        $result->execute();
        // Возвращаем данные
        return $result->fetch();
    }

    
} 