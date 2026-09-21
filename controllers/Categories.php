<?php

require_once __DIR__ . '/../models/CategoryModel.php';

class Categories
{
    private $model;

    public function __construct()
    {
        $this->model = new CategoryModel();
    }

    private function validate($data)
    {
        $errors = [];

        if (empty(trim($data['name'] ?? ''))) {
            $errors['name'] = 'Name is required.';
        }

        return $errors;
    }

    public function index()
    {
        $categories = $this->model->getAll();
        require_once __DIR__ . '/../views/categories/index.php';
    }

    public function create()
    {
        $isEdit = false;
        require_once __DIR__ . '/../views/categories/form.php';
    }

    public function store()
    {
        $errors = $this->validate($_POST);

        if (!empty($errors)) {
            $isEdit = false;
            require_once __DIR__ . '/../views/categories/form.php';
            return;
        }

        $this->model->create([
            'name' => trim($_POST['name']),
        ]);

        header('Location: ' . BASE_URL . '/categories');
        exit;
    }

    public function edit($id)
    {
        $category = $this->model->getById($id);

        if ($category === null) {
            http_response_code(404);
            echo '404 - Category not found';
            return;
        }

        $isEdit = true;
        require_once __DIR__ . '/../views/categories/form.php';
    }

    public function update($id)
    {
        if ($this->model->getById($id) === null) {
            http_response_code(404);
            echo '404 - Category not found';
            return;
        }

        $errors = $this->validate($_POST);

        if (!empty($errors)) {
            $isEdit   = true;
            $category = $this->model->getById($id);
            require_once __DIR__ . '/../views/categories/form.php';
            return;
        }

        $this->model->update($id, [
            'name' => trim($_POST['name']),
        ]);

        header('Location: ' . BASE_URL . '/categories');
        exit;
    }

    public function delete($id)
    {
        if ($this->model->getById($id) === null) {
            http_response_code(404);
            echo '404 - Category not found';
            return;
        }

        $this->model->delete($id);
        header('Location: ' . BASE_URL . '/categories');
        exit;
    }
}
