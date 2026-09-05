<?php $title = 'Product Detail'; require_once __DIR__ . '/../layout/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h4 class="mb-0">Product Detail</h4>
            </div>
            <div class="card-body">
                <table class="table table-borderless mb-0">
                    <tr>
                        <th width="130">ID</th>
                        <td><?= $product['id'] ?></td>
                    </tr>
                    <tr>
                        <th>Name</th>
                        <td><?= htmlspecialchars($product['name']) ?></td>
                    </tr>
                    <tr>
                        <th>Price</th>
                        <td>Rp <?= number_format($product['price'], 0, ',', '.') ?></td>
                    </tr>
                    <tr>
                        <th>Description</th>
                        <td><?= htmlspecialchars($product['description']) ?></td>
                    </tr>
                </table>
            </div>
            <div class="card-footer d-flex gap-2">
                <a href="/pwl/products" class="btn btn-secondary btn-sm">Back</a>
                <a href="/pwl/products/<?= $product['id'] ?>/edit" class="btn btn-warning btn-sm">Edit</a>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
