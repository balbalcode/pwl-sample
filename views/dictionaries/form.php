<?php
$isEdit = in_array($action, ['edit', 'update'], true);
$values = $old ?? $dictionary ?? [];
$title  = $isEdit ? 'Edit Dictionary' : 'Create Dictionary';

require_once __DIR__ . '/../layout/header.php';
?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0"><?= $title ?></h4>
                <p class="text-muted small mb-0"><?= $isEdit ? 'Update the details below' : 'Add a new dictionary entry' ?></p>
            </div>

            <div class="card-body">
                <form action="<?= $isEdit ? BASE_URL . '/dictionaries/' . $id . '/update' : BASE_URL . '/dictionaries/store' ?>" method="POST">

                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" name="name" class="form-control <?= isset($errors['name']) ? 'is-invalid' : '' ?>"
                               value="<?= htmlspecialchars($values['name'] ?? '') ?>">
                        <?php if (isset($errors['name'])): ?>
                            <div class="invalid-feedback"><?= $errors['name'] ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Script</label>
                        <textarea name="script" rows="4" class="form-control <?= isset($errors['script']) ? 'is-invalid' : '' ?>"><?= htmlspecialchars($values['script'] ?? '') ?></textarea>
                        <?php if (isset($errors['script'])): ?>
                            <div class="invalid-feedback"><?= $errors['script'] ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Result</label>
                        <textarea name="result" rows="4" class="form-control <?= isset($errors['result']) ? 'is-invalid' : '' ?>"><?= htmlspecialchars($values['result'] ?? '') ?></textarea>
                        <?php if (isset($errors['result'])): ?>
                            <div class="invalid-feedback"><?= $errors['result'] ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary"><?= $isEdit ? 'Update' : 'Save' ?></button>
                        <a href="<?= BASE_URL ?>/dictionaries" class="btn btn-secondary">Cancel</a>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
