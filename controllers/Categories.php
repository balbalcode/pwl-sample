<?php

require_once __DIR__ . '/../models/CategoryModel.php';

class Categories
{
    private $model;
    private $load;

    public function __construct()
    {
        $this->model = new CategoryModel();
        $this->load  = new Loader();
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
        $this->load->view('views/categories/index.php', [
            'categories' => $categories,
        ]);
    }

    public function create()
    {
        $isEdit = false;
        $this->load->view('views/categories/form.php', [
            'isEdit' => $isEdit,
        ]);
    }

    public function store()
    {
        $errors = $this->validate($_POST);

        if (!empty($errors)) {
            $isEdit = false;
            $this->load->view('views/categories/form.php', [
                'isEdit' => $isEdit,
                'errors' => $errors,
            ]);
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
        $this->load->view('views/categories/form.php', [
            'isEdit'   => $isEdit,
            'id'       => $id,
            'category' => $category,
        ]);
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
            $this->load->view('views/categories/form.php', [
                'isEdit'   => $isEdit,
                'id'       => $id,
                'category' => $category,
                'errors'   => $errors,
            ]);
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
