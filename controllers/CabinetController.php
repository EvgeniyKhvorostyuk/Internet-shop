<?php


class CabinetController
{

	public function actionIndex()
    {
        // Получаем идентификатор пользователя из сессии
        $userId = User::checkLogged();
        
        // Получаем информацию о пользователе из БД
        $user = User::getUserById($userId);
                
        require_once(ROOT . '/views/cabinet/index.php');

        return true;
    }  
    
    public function actionEdit()
    {
        // Получаем идентификатор пользователя из сессии
        $userId = User::checkLogged();
        
        // Получаем информацию о пользователе из БД
        $user = User::getUserById($userId);
        
        $username = $user['username'];
        $email = $user['email'];
        $password = $user['password'];
                
        $result = false;     

        if (isset($_POST['submit'])) {
            $username = $_POST['username'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            
            $errors = false;
            
            if (!User::checkName($username)) {
                $errors[] = 'Имя не должно быть короче 2-х символов';
            }
            if (!User::checkEmail($email)) {
                $errors[] = 'Неправильный email';
            }
        
            if (User::checkEmailExists($email)) {
                $errors[] = 'Такой email уже используется';
            }
            if (User::checkUsernameExists($username)) {
                $errors[] = 'Такое имя уже используется';
            }
            
            if (!User::checkPassword($password)) {
                $errors[] = 'Пароль не должен быть короче 6-ти символов';
            }
            
            if ($errors == false) {
                $result = User::edit($userId, $username, $email, $password);
            }

        }

        require_once(ROOT . '/views/cabinet/edit.php');

        return true;
    }
}