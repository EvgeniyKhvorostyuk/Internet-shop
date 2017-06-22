<?php

class CartController
{


	public function actionAdd($id)
	{
		
		Cart::addProduct($id);

		$referrer = $_SERVER['HTTP_REFERRER'];
		header("Location: $referrer");
	}

	public function actionAddAjax($id)
	{

		echo Cart::addProduct($id);
		return true;
	}

	public function actionIndex()
	{
		$categories = array();
		$categories = Category::getCategoriesList();

		$productsInCart = Cart::getProducts();

		if ($productsInCart) {

			//получаем всю инф о товарах со списка корзины
			$productsIds = array_keys($productsInCart);
			$products = Product::getProductsByIds($productsIds);

			$totalPrice = Cart::getTotalPrice($products);
		}

		require_once(ROOT . '/views/cart/index.php');

		return true;
		
	}

	public function actionCheckout()
	{
		// список для меню категорий
		$categories = array();
		$categories = Category::getCategoriesList();

		$productsInCart = Cart::getProducts();

		//если в корзине нет товаров перенаправляем пользователя на главную
		if ($productsInCart == false) {
			header('Location: /');
		}

		// находим общую стоимость 
		$productsIds = array_keys($productsInCart);
		$products = Product::getProductsByIds($productsIds);
		$totalPrice = Cart::getTotalPrice($products);

		// количество товаров
		$totalQuantity = Cart::countItems();

		//Поля для формы
        $userName = false;
        $userPhone = false;
        $userComment = false;

		// Статус успешного оформления заказа
		$result = false;

		// Проверяем является ли пользователь гостем
		if (!User::isGuest()) {

			// Получаем информацию о пользователе из БД
			$userId = User::checkLogged();
			$user = User::getUserById($userId);
			$userName = $user['username'];
		} else {
			//если пользователь гость поля формы будут пустыми
			$userId = false;
		}

		//если форма была отправлена
		if (isset($_POST['submit'])) {

			$userName = $_POST['userName'];
			$userPhone = $_POST['userPhone'];
			$userComment = $_POST['userComment'];

			$errors = false;

			//валидация полей
			if (!User::checkName($userName)) {
				$errors[] = 'Неправильное имя';
			}
			if (!User::checkPhone($userPhone)) {
                $errors[] = 'Неправильный телефон';
            }

            // Если ошибок нет
            if ($errors == false) {
                
                // Сохраняем заказ в базе данных
                $result = Order::save($userName, $userPhone, $userComment, $userId, $productsInCart);

                // Если заказ успешно сохранен
                if ($result) {
                    
                    // Оповещаем администратора о новом заказе по почте                
                    $adminEmail = 'evgeniykhvorostyuk@gmail.com';
                    $message = '<a href="http://digital-mafia.net/admin/orders">Список заказов</a>';
                    $subject = 'Новый заказ!';
                    mail($adminEmail, $subject, $message);
                    // Очищаем корзину
                    Cart::clearCart();
                }
            }
		}


		require_once(ROOT . '/views/cart/checkout.php');

		return true;
	}
}