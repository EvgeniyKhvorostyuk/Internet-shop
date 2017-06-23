<?php

/**
 * Абстрактный класс AdminBase содержит общую логику для контроллеров, которые 
 * используются в панели администратора
 */
abstract class AdminBase
{
	public static function checkAdmin()
	{
		$userId = User::checkLogged();

		$user = User::getUserById($userId);

		if ($user['role'] == 'admin') {
			return true;
		}

		die('Accesss denied');
	}
}