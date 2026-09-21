<?php

require_once __DIR__ . '/../models/DictionaryModel.php';

class Dictionaries
{
    private $model;
    private $load;

    public function __construct()
    {
        $this->model = new DictionaryModel();
        $this->load  = new Loader();
    }

    private function validate($data)
    {
        $errors = [];

        if (empty(trim($data['script'] ?? ''))) {
            $errors['script'] = 'Script is required.';
        }

        if (empty(trim($data['result'] ?? ''))) {
            $errors['result'] = 'Result is required.';
        }

        return $errors;
    }

    public function index()
    {
        $search       = trim($_GET['q'] ?? '');
        $dictionaries = $this->model->getAll($search);
        $this->load->view('views/dictionaries/index.php', [
            'dictionaries' => $dictionaries,
            'search'       => $search,
        ]);
    }

    public function detail($id)
    {
        $dictionaries = [$this->model->getById($id)];

        if ($dictionaries[0] === null) {
            http_response_code(404);
            echo '404 - Dictionary not found';
            return;
        }

        $this->load->view('views/dictionaries/index.php', [
            'dictionaries' => $dictionaries,
        ]);
    }

    public function create()
    {
        $isEdit = false;
        $this->load->view('views/dictionaries/form.php', [
            'isEdit' => $isEdit,
        ]);
    }

    public function store()
    {
        $errors = $this->validate($_POST);

        if (!empty($errors)) {
            $isEdit = false;
            $this->load->view('views/dictionaries/form.php', [
                'isEdit' => $isEdit,
                'errors' => $errors,
            ]);
            return;
        }

        $this->model->create([
            'name'   => trim($_POST['name'] ?? ''),
            'script' => trim($_POST['script']),
            'result' => trim($_POST['result']),
        ]);

        header('Location: ' . BASE_URL . '/dictionaries');
        exit;
    }

    public function edit($id)
    {
        $dictionary = $this->model->getById($id);

        if ($dictionary === null) {
            http_response_code(404);
            echo '404 - Dictionary not found';
            return;
        }

        $isEdit = true;
        $this->load->view('views/dictionaries/form.php', [
            'isEdit'     => $isEdit,
            'id'         => $id,
            'dictionary' => $dictionary,
        ]);
    }

    public function update($id)
    {
        if ($this->model->getById($id) === null) {
            http_response_code(404);
            echo '404 - Dictionary not found';
            return;
        }

        $errors = $this->validate($_POST);

        if (!empty($errors)) {
            $isEdit     = true;
            $dictionary = $this->model->getById($id);
            $this->load->view('views/dictionaries/form.php', [
                'isEdit'     => $isEdit,
                'id'         => $id,
                'dictionary' => $dictionary,
                'errors'     => $errors,
            ]);
            return;
        }

        $this->model->update($id, [
            'name'   => trim($_POST['name'] ?? ''),
            'script' => trim($_POST['script']),
            'result' => trim($_POST['result']),
        ]);

        header('Location: ' . BASE_URL . '/dictionaries');
        exit;
    }

    public function delete($id)
    {
        if ($this->model->getById($id) === null) {
            http_response_code(404);
            echo '404 - Dictionary not found';
            return;
        }

        $this->model->delete($id);
        header('Location: ' . BASE_URL . '/dictionaries');
        exit;
    }
}
