<?php

require_once __DIR__ . '/../models/ProductModel.php';

class Products
{
    private $model;

    public function __construct()
    {
        $this->model = new ProductModel();
    }

    private function validate($data)
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

    public function index()
    {
        $search   = trim($_GET['q'] ?? '');
        $products = $this->model->getAll($search);
        require_once __DIR__ . '/../views/products/index.php';
    }

    public function detail($id)
    {
        $products = [$this->model->getById($id)];

        if ($products[0] === null) {
            http_response_code(404);
            echo '404 - Product not found';
            return;
        }

        require_once __DIR__ . '/../views/products/index.php';
    }

    public function create()
    {
        $isEdit = false;
        require_once __DIR__ . '/../views/products/form.php';
    }

    public function store()
    {
        $errors = $this->validate($_POST);

        if (!empty($errors)) {
            $isEdit = false;
            require_once __DIR__ . '/../views/products/form.php';
            return;
        }

        $this->model->create([
            'name'        => trim($_POST['name']),
            'price'       => (int) $_POST['price'],
            'description' => trim($_POST['description']),
        ]);

        header('Location: ' . BASE_URL . '/products');
        exit;
    }

    public function edit($id)
    {
        $product = $this->model->getById($id);

        if ($product === null) {
            http_response_code(404);
            echo '404 - Product not found';
            return;
        }

        $isEdit = true;
        require_once __DIR__ . '/../views/products/form.php';
    }

    public function update($id)
    {
        if ($this->model->getById($id) === null) {
            http_response_code(404);
            echo '404 - Product not found';
            return;
        }

        $errors = $this->validate($_POST);

        if (!empty($errors)) {
            $isEdit  = true;
            $product = $this->model->getById($id);
            require_once __DIR__ . '/../views/products/form.php';
            return;
        }

        $this->model->update($id, [
            'name'        => trim($_POST['name']),
            'price'       => (int) $_POST['price'],
            'description' => trim($_POST['description']),
        ]);

        header('Location: ' . BASE_URL . '/products');
        exit;
    }

    public function delete($id)
    {
        if ($this->model->getById($id) === null) {
            http_response_code(404);
            echo '404 - Product not found';
            return;
        }

        $this->model->delete($id);
        header('Location: ' . BASE_URL . '/products');
        exit;
    }
}
