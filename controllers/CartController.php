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
}