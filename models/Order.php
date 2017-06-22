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
} 