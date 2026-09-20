<?php

require_once __DIR__ . '/../models/CategoryModel.php';

$model = new CategoryModel();

function validate($data)
{
    $errors = [];

    if (empty(trim($data['name'] ?? ''))) {
        $errors['name'] = 'Name is required.';
    }

    return $errors;
}

switch ("$method:$action") {

    case 'GET:index':
        $categories = $model->getAll();
        require_once __DIR__ . '/../views/categories/index.php';
        break;

    case 'GET:create':
        $isEdit = false;
        require_once __DIR__ . '/../views/categories/form.php';
        break;

    case 'POST:store':
        $errors = validate($_POST);

        if (!empty($errors)) {
            $isEdit = false;
            require_once __DIR__ . '/../views/categories/form.php';
            break;
        }

        $model->create([
            'name' => trim($_POST['name']),
        ]);

        header('Location: ' . BASE_URL . '/categories');
        exit;

    case 'GET:edit':
        $category = $model->getById($id);

        if ($category === null) {
            http_response_code(404);
            echo '404 - Category not found';
            break;
        }

        $isEdit = true;
        require_once __DIR__ . '/../views/categories/form.php';
        break;

    case 'POST:update':
        if ($model->getById($id) === null) {
            http_response_code(404);
            echo '404 - Category not found';
            break;
        }

        $errors = validate($_POST);

        if (!empty($errors)) {
            $isEdit   = true;
            $category = $model->getById($id);
            require_once __DIR__ . '/../views/categories/form.php';
            break;
        }

        $model->update($id, [
            'name' => trim($_POST['name']),
        ]);

        header('Location: ' . BASE_URL . '/categories');
        exit;

    case 'POST:delete':
        if ($model->getById($id) === null) {
            http_response_code(404);
            echo '404 - Category not found';
            break;
        }

        $model->delete($id);
        header('Location: ' . BASE_URL . '/categories');
        exit;

    default:
        http_response_code(404);
        echo '404 - Action not found';
        break;
}
