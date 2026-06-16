<?php
require_once __DIR__ . '/../includes/functions.php';
requireAdmin();

$productId = isset($_GET['id']) ? (int)$_GET['id'] : null;
$pageTitle = $productId ? 'Edit Product' : 'Add Product';

$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();

$product = [
    'name' => '', 'category_id' => '', 'description' => '', 'price' => '',
    'unit' => 'kg', 'stock_quantity' => '', 'image' => '', 'is_featured' => 0, 'status' => 'active'
];

if ($productId) {
    $stmt = $pdo->prepare("SELECT * FROM products WHERE product_id = ?");
    $stmt->execute([$productId]);
    $found = $stmt->fetch();
    if ($found) $product = $found;
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $categoryId = (int)($_POST['category_id'] ?? 0);
    $description = trim($_POST['description'] ?? '');
    $price = (float)($_POST['price'] ?? 0);
    $unit = trim($_POST['unit'] ?? 'kg');
    $stock = (int)($_POST['stock_quantity'] ?? 0);
    $isFeatured = isset($_POST['is_featured']) ? 1 : 0;
    $status = $_POST['status'] ?? 'active';
    $image = $product['image'];

    if ($name === '') $errors[] = 'Product name is required.';
    if ($categoryId <= 0) $errors[] = 'Please select a category.';
    if ($price <= 0) $errors[] = 'Price must be greater than 0.';

    // Handle image upload
    if (!empty($_FILES['image']['name'])) {
        $allowedExt = ['jpg','jpeg','png','webp'];
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, $allowedExt)) {
            $newName = uniqid('prod_') . '.' . $ext;
            $dest = __DIR__ . '/../assets/images/products/' . $newName;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
                $image = $newName;
            } else {
                $errors[] = 'Failed to upload image.';
            }
        } else {
            $errors[] = 'Invalid image format. Use JPG, PNG or WEBP.';
        }
    }

    // Generate slug
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name), '-'));

    if (empty($errors)) {
        if ($productId) {
            $stmt = $pdo->prepare("UPDATE products SET category_id=?, name=?, slug=?, description=?, price=?, unit=?, stock_quantity=?, image=?, is_featured=?, status=? WHERE product_id=?");
            $stmt->execute([$categoryId, $name, $slug, $description, $price, $unit, $stock, $image, $isFeatured, $status, $productId]);
        } else {
            $stmt = $pdo->prepare("INSERT INTO products (category_id, name, slug, description, price, unit, stock_quantity, image, is_featured, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([$categoryId, $name, $slug, $description, $price, $unit, $stock, $image ?: 'placeholder.jpg', $isFeatured, $status]);
        }
        redirect('products.php');
    }

    // Keep posted values on error
    $product = array_merge($product, [
        'name' => $name, 'category_id' => $categoryId, 'description' => $description,
        'price' => $price, 'unit' => $unit, 'stock_quantity' => $stock,
        'image' => $image, 'is_featured' => $isFeatured, 'status' => $status
    ]);
}

require __DIR__ . '/includes/header.php';
?>

<div class="admin-form-card">
    <?php if (!empty($errors)): ?>
        <div class="alert alert-error">
            <ul style="margin-left:18px;">
                <?php foreach ($errors as $err): ?><li><?= e($err) ?></li><?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data">
        <div class="form-group">
            <label>Product Name *</label>
            <input type="text" name="name" value="<?= e($product['name']) ?>" required>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Category *</label>
                <select name="category_id" required>
                    <option value="">-- Select Category --</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['category_id'] ?>" <?= $product['category_id'] == $cat['category_id'] ? 'selected' : '' ?>>
                            <?= e($cat['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Unit</label>
                <select name="unit">
                    <?php foreach (['kg','piece','bunch','litre','bag','tray','bottle'] as $u): ?>
                        <option value="<?= $u ?>" <?= $product['unit'] === $u ? 'selected' : '' ?>><?= ucfirst($u) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>
        <div class="form-group">
            <label>Description</label>
            <textarea name="description" rows="4"><?= e($product['description']) ?></textarea>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Price (RWF) *</label>
                <input type="number" name="price" step="0.01" value="<?= e($product['price']) ?>" required>
            </div>
            <div class="form-group">
                <label>Stock Quantity</label>
                <input type="number" name="stock_quantity" value="<?= e($product['stock_quantity']) ?>">
            </div>
        </div>
        <div class="form-group">
            <label>Product Image</label>
            <input type="file" name="image" accept=".jpg,.jpeg,.png,.webp">
            <?php if ($product['image']): ?>
                <p style="margin-top:8px;"><img src="../assets/images/products/<?= e($product['image']) ?>" style="width:60px;height:60px;object-fit:cover;border-radius:8px;" onerror="this.style.display='none'"></p>
            <?php endif; ?>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label>Status</label>
                <select name="status">
                    <option value="active" <?= $product['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                    <option value="inactive" <?= $product['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                </select>
            </div>
            <div class="form-group" style="display:flex;align-items:center;gap:10px;margin-top:28px;">
                <input type="checkbox" name="is_featured" id="is_featured" style="width:auto;" <?= $product['is_featured'] ? 'checked' : '' ?>>
                <label for="is_featured" style="margin:0;">Featured Product</label>
            </div>
        </div>

        <button type="submit" class="btn btn-primary">Save Product</button>
        <a href="products.php" class="btn btn-outline">Cancel</a>
    </form>
</div>

<?php require __DIR__ . '/includes/footer.php'; ?>
