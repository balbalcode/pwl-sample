<?php

require_once __DIR__ . '/../models/ProductModel.php';

$model = new ProductModel();

function validate($data)
{
    $errors = [];

    if (empty(trim($data['name'] ?? ''))) {
        $errors['name'] = 'Name is required.';
    }

    if (empty(trim($data['price'] ?? ''))) {
        $errors['price'] = 'Price is required.';
    } elseif (!is_numeric($data['price']) || $data['price'] < 0) {
        $errors['price'] = 'Price must be a positive number.';
    }

    if (empty(trim($data['description'] ?? ''))) {
        $errors['description'] = 'Description is required.';
    }

    return $errors;
}

switch ("$method:$action") {

    case 'GET:index':
        $search   = trim($_GET['q'] ?? '');
        $products = $model->getAll($search);
        require_once __DIR__ . '/../views/products/index.php';
        break;

    case 'GET:detail':
        $products = [$model->getById($id)];
        if ($products[0] === null) {
            http_response_code(404);
            echo '404 - Product not found';
            break;
        }
        require_once __DIR__ . '/../views/products/index.php';
        break;

    case 'GET:create':
        require_once __DIR__ . '/../views/products/form.php';
        break;

    case 'POST:store':
        $errors = validate($_POST);

        if (!empty($errors)) {
            $old = $_POST;
            require_once __DIR__ . '/../views/products/form.php';
            break;
        }

        $model->create([
            'name'        => trim($_POST['name']),
            'price'       => (int) $_POST['price'],
            'description' => trim($_POST['description']),
        ]);

        header('Location: ' . BASE_URL . '/products');
        exit;

    case 'GET:edit':
        $product = $model->getById($id);

        if ($product === null) {
            http_response_code(404);
            echo '404 - Product not found';
            break;
        }

        require_once __DIR__ . '/../views/products/form.php';
        break;

    case 'POST:update':
        if ($model->getById($id) === null) {
            http_response_code(404);
            echo '404 - Product not found';
            break;
        }

        $errors = validate($_POST);

        if (!empty($errors)) {
            $old = $_POST;
            require_once __DIR__ . '/../views/products/form.php';
            break;
        }

        $model->update($id, [
            'name'        => trim($_POST['name']),
            'price'       => (int) $_POST['price'],
            'description' => trim($_POST['description']),
        ]);

        header('Location: ' . BASE_URL . '/products');
        exit;

    case 'POST:delete':
        if ($model->getById($id) === null) {
            http_response_code(404);
            echo '404 - Product not found';
            break;
        }

        $model->delete($id);
        header('Location: ' . BASE_URL . '/products');
        exit;

    default:
        http_response_code(404);
        echo '404 - Action not found';
        break;
}
