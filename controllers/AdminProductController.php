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

	public function actionCreate() 
	{
		self::checkAdmin();

		// список категорий для выпадающего списка
		$categoriesList = Category::getCategoriesListAdmin();

		if (isset($_POST['submit'])) {

			// Получаем данные из формы
            $options['name'] = $_POST['name'];
            $options['code'] = $_POST['code'];
            $options['price'] = $_POST['price'];
            $options['category_id'] = $_POST['category_id'];
            $options['brand'] = $_POST['brand'];
            $options['availability'] = $_POST['availability'];
            $options['description'] = $_POST['description'];
            $options['is_new'] = $_POST['is_new'];
            $options['is_recommended'] = $_POST['is_recommended'];
            $options['status'] = $_POST['status'];

            $errors = false;

            if (!isset($options['name']) || empty($options['name'])) {
            	$errors[] = 'Заполните поле!';
            }

            if ($errors == false)  {

            	$id = Product::createProduct($options);

            	// Если запись добавлена
                if ($id) {
                    
                    // Проверим, загружалось ли через форму изображение
                    if (is_uploaded_file($_FILES["image"]["tmp_name"])) {
                        
                        // Если загружалось, переместим его в нужную папке, дадим новое имя
                        move_uploaded_file($_FILES["image"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . "/upload/images/products/{$id}.jpg");
                    }
                };
                
                // Перенаправляем пользователя на страницу управлениями товарами
                header("Location: /admin/product");
            }
		}

		require_once(ROOT . '/views/admin_product/create.php');
        return true;

	}

	public function actionUpdate($id)
	{
		self::checkAdmin();

		$categoriesList = Category::getCategoriesListAdmin();

		$product = Product::getProductById($id);

		if (isset($_POST['submit'])) {

			$options['name'] = $_POST['name'];
            $options['code'] = $_POST['code'];
            $options['price'] = $_POST['price'];
            $options['category_id'] = $_POST['category_id'];
            $options['brand'] = $_POST['brand'];
            $options['availability'] = $_POST['availability'];
            $options['description'] = $_POST['description'];
            $options['is_new'] = $_POST['is_new'];
            $options['is_recommended'] = $_POST['is_recommended'];
            $options['status'] = $_POST['status'];

            //save changes
            if (Product::updateProductById($id, $options)) {

            	// Проверим, загружалось ли через форму изображение
                if (is_uploaded_file($_FILES["image"]["tmp_name"])) {
                   
                   // Если загружалось, переместим его в нужную папке, дадим новое имя
                   move_uploaded_file($_FILES["image"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . "/upload/images/products/{$id}.jpg");
                }
            }

            header('Location: /admin/product');
		}

		require_once(ROOT . '/views/admin_product/update.php');
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