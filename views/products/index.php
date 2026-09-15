<?php require_once __DIR__ . '/../layout/header.php'; ?>

<div class="d-flex justify-content-between align-items-center gap-2 flex-wrap mb-3">
    <form action="<?= BASE_URL ?>/products" method="GET" class="d-flex" role="search">
        <input type="text" name="q" class="form-control" placeholder="Search products..."
               value="<?= htmlspecialchars($search ?? '') ?>">
        <button type="submit" class="btn btn-outline-secondary ms-2">
            <i class="bi bi-search"></i>
        </button>
    </form>

    <a href="<?= BASE_URL ?>/products/create" class="btn btn-primary">
        <i class="bi bi-plus-lg"></i> Add Product
    </a>
</div>

<?php if (empty($products)): ?>
    <div class="card text-center p-5">
        <i class="bi bi-inbox text-muted" style="font-size: 2.5rem;"></i>
        <?php if (!empty($search)): ?>
            <p class="mt-3 mb-3 text-muted">No products match "<?= htmlspecialchars($search) ?>".</p>
            <a href="<?= BASE_URL ?>/products" class="btn btn-outline-secondary btn-sm">Clear search</a>
        <?php else: ?>
            <p class="mt-3 mb-3 text-muted">No products found yet.</p>
            <a href="<?= BASE_URL ?>/products/create" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-lg"></i> Add your first product
            </a>
        <?php endif; ?>
    </div>
<?php else: ?>
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Price</th>
                        <th>Description</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $product): ?>
                    <tr>
                        <td><span class="badge bg-light text-dark font-monospace fw-normal"><?= substr($product['id'], 0, 8) ?></span></td>
                        <td class="fw-semibold"><?= htmlspecialchars($product['name']) ?></td>
                        <td><span class="text-success fw-bold">Rp <?= number_format($product['price'], 0, ',', '.') ?></span></td>
                        <td class="text-muted"><?= htmlspecialchars($product['description']) ?></td>
                        <td class="text-end">
                            <a href="<?= BASE_URL ?>/products/<?= $product['id'] ?>/edit" class="btn btn-outline-warning btn-sm">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <form action="<?= BASE_URL ?>/products/<?= $product['id'] ?>/delete" method="POST" class="d-inline"
                                  onsubmit="return confirm('Are you sure?')">
                                <button type="submit" class="btn btn-outline-danger btn-sm">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

<?php require_once __DIR__ . '/../layout/footer.php'; ?>
