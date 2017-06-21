<?php

class CartController
{


	public function actionAdd($id)
	{
		
		Cart::addProduct($id);

		$referrer = $_SERVER['HTTP_REFERRER'];
		header("Location: $referrer");
	}
}