<?php

require_once __DIR__ . '/../models/DictionaryModel.php';

$model = new DictionaryModel();

function validate($data)
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

switch ("$method:$action") {

    case 'GET:index':
        $search      = trim($_GET['q'] ?? '');
        $dictionaries = $model->getAll($search);
        require_once __DIR__ . '/../views/dictionaries/index.php';
        break;

    case 'GET:detail':
        $dictionaries = [$model->getById($id)];
        if ($dictionaries[0] === null) {
            http_response_code(404);
            echo '404 - Dictionary not found';
            break;
        }
        require_once __DIR__ . '/../views/dictionaries/index.php';
        break;

    case 'GET:create':
        require_once __DIR__ . '/../views/dictionaries/form.php';
        break;

    case 'POST:store':
        $errors = validate($_POST);

        if (!empty($errors)) {
            $old = $_POST;
            require_once __DIR__ . '/../views/dictionaries/form.php';
            break;
        }

        $model->create([
            'name'   => trim($_POST['name'] ?? ''),
            'script' => trim($_POST['script']),
            'result' => trim($_POST['result']),
        ]);

        header('Location: ' . BASE_URL . '/dictionaries');
        exit;

    case 'GET:edit':
        $dictionary = $model->getById($id);

        if ($dictionary === null) {
            http_response_code(404);
            echo '404 - Dictionary not found';
            break;
        }

        require_once __DIR__ . '/../views/dictionaries/form.php';
        break;

    case 'POST:update':
        if ($model->getById($id) === null) {
            http_response_code(404);
            echo '404 - Dictionary not found';
            break;
        }

        $errors = validate($_POST);

        if (!empty($errors)) {
            $old = $_POST;
            require_once __DIR__ . '/../views/dictionaries/form.php';
            break;
        }

        $model->update($id, [
            'name'   => trim($_POST['name'] ?? ''),
            'script' => trim($_POST['script']),
            'result' => trim($_POST['result']),
        ]);

        header('Location: ' . BASE_URL . '/dictionaries');
        exit;

    case 'POST:delete':
        if ($model->getById($id) === null) {
            http_response_code(404);
            echo '404 - Dictionary not found';
            break;
        }

        $model->delete($id);
        header('Location: ' . BASE_URL . '/dictionaries');
        exit;

    default:
        http_response_code(404);
        echo '404 - Action not found';
        break;
}
