<?php

class AdminOrderController extends AdminBase
{
	public function actionIndex()
	{
		self::checkAdmin();

		$ordersList = Order::getOrderListAdmin();

		require_once(ROOT . '/views/admin_order/index.php');

		return true;
	}

	public function actionView($id)
	{
		self::checkAdmin();

		$order = Order::getOrderById($id);

		//массив с товарами заказа
		$productsQuantity = json_decode($order['products'], true);

		$productsIds = array_keys($productsQuantity);

		$products = Product::getProductsByIds($productsIds);

		require_once(ROOT . '/views/admin_order/view.php');

		return true;
	}	
}