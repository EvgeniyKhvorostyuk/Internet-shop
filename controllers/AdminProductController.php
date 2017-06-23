<?php

class AdminProductController extends AdminBase
{
	public function actionIndex()
	{
		self::checkAdmin();

		$productsList = [];
		$productsList = Product::getProductsList();

		require_once(ROOT . '/views/admin_product/index.php');

		return true;
	}

	public function actionDelete($id)
	{
		self::checkAdmin();

		if (isset($_POST['submit'])) {

			Product::deleteProductById($id);

			header('Location: /admin/product');
		}

		require_once(ROOT . '/views/admin_product/delete.php');

		return true;
	}
}