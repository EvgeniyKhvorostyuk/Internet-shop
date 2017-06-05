<?php


class UserController
{
	public function actionRegister()
    {
        // Переменные для формы
        $username = false;

        $email = false;
        
        $password = false;
        
        $result = false;
        
        // Если форма отправлена
        if (isset($_POST['submit'])) { 
            
            // Получаем данные из формы
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            
            // Флаг ошибок
            $errors = false;
            
            // Валидация полей
            if (!User::checkName($username)) {
                $errors[] = 'Имя не должно быть короче 2-х символов';
            }
            if (!User::checkEmail($email)) {
                $errors[] = 'Неправильный email';
            }
            if (!User::checkPassword($password)) {
                $errors[] = 'Пароль не должен быть короче 6-ти символов';
            }
            if (User::checkEmailExists($email)) {
                $errors[] = 'Такой email уже используется';
            }
            if (User::checkUsernameExists($username)) {
                $errors[] = 'Такое имя уже используется';
            }
            
            // Если ошибок нет
            if ($errors == false) {
                
                // Регистрируем пользователя
                $result = User::register($username, $email, $password);

                // Проверяем существует ли пользователь
                $userId = User::checkUserData($email, $password);
                // Выполняем вход на сайт
                User::auth($userId);
                // Перенаправляем пользователя в закрытую часть - кабинет 
                header("Location: /cabinet");
            }
        }
        
        require_once(ROOT . '/views/user/register.php');
        return true;
    }
    
    public function actionLogin()
    {
        // Переменные для формы
        $email = false;

        $password = false;
        
        // Если форма отправлена
        if (isset($_POST['submit'])) { 
            
            // Получаем данные из формы
            $email = $_POST['email'];
            $password = $_POST['password'];
            
            // Флаг ошибок
            $errors = false;
            
            // Валидация полей
            if (!User::checkEmail($email)) {
                $errors[] = 'Неправильный email';
            }
            if (!User::checkPassword($password)) {
                $errors[] = 'Пароль не должен быть короче 6-ти символов';
            }
            // Проверяем существует ли пользователь
            $userId = User::checkUserData($email, $password);
            
            if ($userId == false) {
                // Если данные неправильные - показываем ошибку
                $errors[] = 'Неправильные данные для входа на сайт';
            } else {
                
                // Если данные правильные, запоминаем пользователя (сессия)
                User::auth($userId);
                // Перенаправляем пользователя в закрытую часть - кабинет 
                header("Location: /cabinet");
            }
        }
        
        require_once(ROOT . '/views/user/login.php');
        return true;
    }
   

    public function actionLogout()
    {
        session_start();
        
        // Удаляем информацию о пользователе из сессии
        unset($_SESSION["user"]);
        
        // Перенаправляем пользователя на главную страницу
        header("Location: /");
    }
    
}