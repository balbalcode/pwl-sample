<?php $title = 'All Products'; require_once __DIR__ . '/../layout/header.php'; ?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h2>Products</h2>
    <a href="/pwl/products/create" class="btn btn-primary">+ New Product</a>
</div>

<?php if (empty($products)): ?>
    <div class="alert alert-info">No products found.</div>
<?php else: ?>
    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Price</th>
                <th>Description</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($products as $product): ?>
            <tr>
                <td><?= $product['id'] ?></td>
                <td><?= htmlspecialchars($product['name']) ?></td>
                <td>Rp <?= number_format($product['price'], 0, ',', '.') ?></td>
                <td><?= htmlspecialchars($product['description']) ?></td>
                <td>
                    <a href="/pwl/products/<?= $product['id'] ?>" class="btn btn-info btn-sm">View</a>
                    <a href="/pwl/products/<?= $product['id'] ?>/edit" class="btn btn-warning btn-sm">Edit</a>

                    <form action="/pwl/products/<?= $product['id'] ?>/delete" method="POST" class="d-inline"
                          onsubmit="return confirm('Are you sure?')">
                        <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
