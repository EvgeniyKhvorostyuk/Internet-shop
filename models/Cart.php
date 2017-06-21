<?php

class Cart
{


	public static function addProduct($id)
	{
		$productsInCart = array();

		if (isset($_SESSION['products'])) {

			$productsInCart = $_SESSION['products'];
		}


		//если товар есть в корзине но не был добавлен еще раз, увеличеваем количество
		if (array_key_exists($id, $productsInCart)) {
			$productsInCart[$id] ++;
		} else {

			//добавляем новый товар
			$productsInCart[$id] = 1;
		}

		$_SESSION['products'] = $productsInCart;

		//возвращаем количество товаров
		return self::countItems();
	}

	//счетчик товаров в корзине(сессии)
	public static function countItems()
	{

		if (isset($_SESSION['products'])) {
			$count = 0;
			foreach ($_SESSION['products'] as $id => $quantity) {
				$count = $count + $quantity;
			}

			return $count;

		} else {

			return 0;
		}
	}

	// если есть товары - возвращаем их
	public static function getProducts()
	{

		if (isset($_SESSION['products'])) {

			return $_SESSION['products'];
		}

		return false;
	}


	public static function getTotalPrice($products)
	{
		$productsInCart = self::getProducts();

		$total = 0;

		//усли есть товары в корзине(сессии)
		if ($productsInCart) {

			foreach ($products as $item) {
				$total += $item['price'] * $productsInCart[$item['id']];
			}
		}

		return $total;
	}
}