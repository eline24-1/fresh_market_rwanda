<?php
require_once __DIR__ . '/../includes/functions.php';
requireAdmin();

$pageTitle = 'Add Product';

$categories = $pdo->query("SELECT * FROM categories ORDER BY name")->fetchAll();

$product = [
    'name' => '', 'category_id' => '', 'description' => '', 'price' => '',
    'unit' => 'kg', 'stock_quantity' => '', 'image' => '', 'is_featured' => 0, 'status' => 'active'
];

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name        = trim($_POST['name'] ?? '');
    $categoryId  = (int)($_POST['category_id'] ?? 0);
    $description = trim($_POST['description'] ?? '');
    $price       = (float)($_POST['price'] ?? 0);
    $unit        = trim($_POST['unit'] ?? 'kg');
    $stock       = (int)($_POST['stock_quantity'] ?? 0);
    $isFeatured  = isset($_POST['is_featured']) ? 1 : 0;
    $status      = in_array($_POST['status'] ?? '', ['active', 'inactive']) ? $_POST['status'] : 'active';
    $image       = 'placeholder.jpg';

    // Validation
    if ($name === '')       $errors[] = 'Product name is required.';
    if ($categoryId <= 0)   $errors[] = 'Please select a category.';
    if ($price <= 0)        $errors[] = 'Price must be greater than 0.';
    if ($stock < 0)         $errors[] = 'Stock quantity cannot be negative.';

    // Handle image upload
    if (!empty($_FILES['image']['name'])) {
        $allowedExt = ['jpg', 'jpeg', 'png', 'webp'];
        $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
        if (in_array($ext, $allowedExt)) {
            $newName = uniqid('prod_') . '.' . $ext;
            $dest    = __DIR__ . '/../assets/images/products/' . $newName;
            if (move_uploaded_file($_FILES['image']['tmp_name'], $dest)) {
                $image = $newName;
            } else {
                $errors[] = 'Failed to upload image. Check folder permissions.';
            }
        } else {
            $errors[] = 'Invalid image format. Accepted: JPG, PNG, WEBP.';
        }
    }

    // Generate slug
    $slug = strtolower(trim(preg_replace('/[^A-Za-z0-9-]+/', '-', $name), '-'));

    if (empty($errors)) {
        $stmt = $pdo->prepare(
            "INSERT INTO products (category_id, name, slug, description, price, unit, stock_quantity, image, is_featured, status)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
        );
        $stmt->execute([$categoryId, $name, $slug, $description, $price, $unit, $stock, $image, $isFeatured, $status]);
        redirect('products.php');
    }

    // Keep posted values on validation error
    $product = array_merge($product, [
        'name'           => $name,
        'category_id'    => $categoryId,
        'description'    => $description,
        'price'          => $price,
        'unit'           => $unit,
        'stock_quantity' => $stock,
        'is_featured'    => $isFeatured,
        'status'         => $status,
    ]);
}

require __DIR__ . '/includes/header.php';
?>

<style>
/* ── Page-level overrides ────────────────────────────────────── */
.ap-card {
    background: #fff;
    border-radius: 14px;
    padding: 36px 40px;
    max-width: 820px;
    box-shadow: 0 2px 16px rgba(0,0,0,.07);
}
.ap-section-title {
    font-size: .72rem;
    font-weight: 700;
    letter-spacing: .1em;
    text-transform: uppercase;
    color: #2e7d4f;
    margin: 28px 0 14px;
    padding-bottom: 6px;
    border-bottom: 2px solid #e8f5e9;
}
.ap-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
.ap-grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 20px; }
.form-group label { font-weight: 600; font-size: .85rem; color: #374151; margin-bottom: 6px; display: block; }
.form-group input,
.form-group select,
.form-group textarea {
    width: 100%; padding: 10px 14px; border: 1.5px solid #d1d5db;
    border-radius: 8px; font-size: .9rem; transition: border-color .2s;
    font-family: inherit; background: #fafafa; color: #111;
}
.form-group input:focus,
.form-group select:focus,
.form-group textarea:focus { outline: none; border-color: #2e7d4f; background: #fff; }
.form-group textarea { resize: vertical; min-height: 100px; }
.required-star { color: #dc2626; margin-left: 2px; }

/* Image drop zone */
.image-dropzone {
    border: 2px dashed #c6e9d4; border-radius: 10px;
    padding: 28px 20px; text-align: center; cursor: pointer;
    transition: border-color .2s, background .2s; background: #f6fdf8;
}
.image-dropzone:hover,
.image-dropzone.drag-over { border-color: #2e7d4f; background: #e8f5e9; }
.image-dropzone i { font-size: 2rem; color: #6db88e; margin-bottom: 10px; }
.image-dropzone p { font-size: .85rem; color: #6b7280; margin: 0; }
.image-dropzone input[type="file"] { display: none; }
#imagePreviewWrap { margin-top: 14px; display: none; text-align: center; }
#imagePreview { width: 100px; height: 100px; object-fit: cover; border-radius: 10px; border: 2px solid #e8f5e9; }
.remove-image-btn { display: block; margin: 6px auto 0; font-size: .78rem; color: #dc2626; background: none; border: none; cursor: pointer; }

/* Toggle switch for featured */
.toggle-wrap { display: flex; align-items: center; gap: 12px; margin-top: 6px; }
.toggle { position: relative; width: 42px; height: 24px; }
.toggle input { opacity: 0; width: 0; height: 0; }
.toggle-slider {
    position: absolute; inset: 0; background: #d1d5db; border-radius: 24px;
    cursor: pointer; transition: background .2s;
}
.toggle-slider::before {
    content: ''; position: absolute; width: 18px; height: 18px;
    left: 3px; bottom: 3px; background: #fff; border-radius: 50%;
    transition: transform .2s;
}
.toggle input:checked + .toggle-slider { background: #2e7d4f; }
.toggle input:checked + .toggle-slider::before { transform: translateX(18px); }
.toggle-label { font-size: .88rem; color: #374151; font-weight: 500; }

/* Status pills in select */
.status-hint { font-size: .76rem; color: #6b7280; margin-top: 5px; }

/* Action bar */
.ap-actions { display: flex; gap: 14px; margin-top: 32px; align-items: center; }
.btn-reset { background: none; border: 1.5px solid #d1d5db; color: #6b7280;
             padding: 10px 22px; border-radius: 8px; cursor: pointer; font-size: .9rem;
             transition: border-color .2s; font-family: inherit; }
.btn-reset:hover { border-color: #9ca3af; color: #374151; }

/* Alert */
.alert-error { background: #fef2f2; border: 1px solid #fca5a5; border-radius: 8px;
               padding: 14px 18px; margin-bottom: 24px; }
.alert-error ul { margin: 6px 0 0 18px; color: #dc2626; font-size: .88rem; }

/* Char counter */
.char-counter { font-size: .74rem; color: #9ca3af; text-align: right; margin-top: 4px; }

@media (max-width: 620px) {
    .ap-grid-2, .ap-grid-3 { grid-template-columns: 1fr; }
    .ap-card { padding: 22px 16px; }
}
</style>

<div class="ap-card">

    <?php if (!empty($errors)): ?>
        <div class="alert-error">
            <strong>Please fix the following errors:</strong>
            <ul>
                <?php foreach ($errors as $err): ?>
                    <li><?= e($err) ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form method="post" enctype="multipart/form-data" id="addProductForm" novalidate>

        <!-- ── Basic Info ───────────────────────────────────── -->
        <div class="ap-section-title">Basic Information</div>

        <div class="form-group">
            <label>Product Name <span class="required-star">*</span></label>
            <input type="text" name="name" id="productName"
                   value="<?= e($product['name']) ?>"
                   placeholder="e.g. Fresh Tomatoes" maxlength="120" required>
        </div>

        <div class="ap-grid-2">
            <div class="form-group">
                <label>Category <span class="required-star">*</span></label>
                <select name="category_id" required>
                    <option value="">— Select category —</option>
                    <?php foreach ($categories as $cat): ?>
                        <option value="<?= $cat['category_id'] ?>"
                            <?= $product['category_id'] == $cat['category_id'] ? 'selected' : '' ?>>
                            <?= e($cat['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label>Unit</label>
                <select name="unit">
                    <?php foreach (['kg','piece','bunch','litre','bag','tray','bottle'] as $u): ?>
                        <option value="<?= $u ?>" <?= $product['unit'] === $u ? 'selected' : '' ?>>
                            <?= ucfirst($u) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="form-group">
            <label>Description</label>
            <textarea name="description" id="descriptionField" maxlength="600"
                      placeholder="Describe the product — freshness, origin, usage tips…"><?= e($product['description']) ?></textarea>
            <div class="char-counter"><span id="descCount">0</span> / 600</div>
        </div>

        <!-- ── Pricing & Stock ──────────────────────────────── -->
        <div class="ap-section-title">Pricing &amp; Stock</div>

        <div class="ap-grid-3">
            <div class="form-group">
                <label>Price (RWF) <span class="required-star">*</span></label>
                <input type="number" name="price" min="1" step="1"
                       value="<?= e($product['price']) ?>"
                       placeholder="0" required>
            </div>
            <div class="form-group">
                <label>Stock Quantity</label>
                <input type="number" name="stock_quantity" min="0" step="1"
                       value="<?= e($product['stock_quantity']) ?>"
                       placeholder="0">
            </div>
            <div class="form-group">
                <label>Status</label>
                <select name="status">
                    <option value="active"   <?= $product['status'] === 'active'   ? 'selected' : '' ?>>Active</option>
                    <option value="inactive" <?= $product['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                </select>
                <div class="status-hint">Inactive products are hidden from customers.</div>
            </div>
        </div>

        <!-- ── Image ────────────────────────────────────────── -->
        <div class="ap-section-title">Product Image</div>

        <div class="image-dropzone" id="dropzone" onclick="document.getElementById('imageInput').click()">
            <i class="fa-solid fa-cloud-arrow-up"></i>
            <p><strong>Click to upload</strong> or drag &amp; drop here</p>
            <p style="margin-top:4px;">JPG, PNG or WEBP — max 5 MB</p>
            <input type="file" name="image" id="imageInput" accept=".jpg,.jpeg,.png,.webp">
        </div>
        <div id="imagePreviewWrap">
            <img id="imagePreview" src="" alt="Preview">
            <button type="button" class="remove-image-btn" onclick="removeImage()">
                <i class="fa-solid fa-xmark"></i> Remove image
            </button>
        </div>

        <!-- ── Settings ─────────────────────────────────────── -->
        <div class="ap-section-title">Settings</div>

        <div class="toggle-wrap">
            <label class="toggle">
                <input type="checkbox" name="is_featured" id="isFeatured"
                    <?= $product['is_featured'] ? 'checked' : '' ?>>
                <span class="toggle-slider"></span>
            </label>
            <span class="toggle-label">Feature this product on the homepage</span>
        </div>

        <!-- ── Actions ──────────────────────────────────────── -->
        <div class="ap-actions">
            <button type="submit" class="btn btn-primary">
                <i class="fa-solid fa-plus"></i> Add Product
            </button>
            <button type="reset" class="btn-reset" onclick="resetForm()">Reset</button>
            <a href="products.php" class="btn btn-outline">Cancel</a>
        </div>

    </form>
</div>

<script>
// ── Description char counter ──────────────────────────────────
const descField  = document.getElementById('descriptionField');
const descCount  = document.getElementById('descCount');
function updateCount() { descCount.textContent = descField.value.length; }
descField.addEventListener('input', updateCount);
updateCount();

// ── Image preview ─────────────────────────────────────────────
const imageInput   = document.getElementById('imageInput');
const previewWrap  = document.getElementById('imagePreviewWrap');
const previewImg   = document.getElementById('imagePreview');
const dropzone     = document.getElementById('dropzone');

imageInput.addEventListener('change', handleFile);

dropzone.addEventListener('dragover', e => { e.preventDefault(); dropzone.classList.add('drag-over'); });
dropzone.addEventListener('dragleave',()  => dropzone.classList.remove('drag-over'));
dropzone.addEventListener('drop', e => {
    e.preventDefault();
    dropzone.classList.remove('drag-over');
    if (e.dataTransfer.files.length) {
        const dt = new DataTransfer();
        dt.items.add(e.dataTransfer.files[0]);
        imageInput.files = dt.files;
        handleFile();
    }
});

function handleFile() {
    const file = imageInput.files[0];
    if (!file) return;
    if (file.size > 5 * 1024 * 1024) { alert('Image must be smaller than 5 MB.'); return; }
    const reader = new FileReader();
    reader.onload = e => {
        previewImg.src       = e.target.result;
        previewWrap.style.display = 'block';
        dropzone.style.display    = 'none';
    };
    reader.readAsDataURL(file);
}

function removeImage() {
    imageInput.value          = '';
    previewWrap.style.display = 'none';
    dropzone.style.display    = 'block';
    previewImg.src            = '';
}

// ── Form reset ────────────────────────────────────────────────
function resetForm() {
    removeImage();
    descCount.textContent = '0';
}
</script>

<?php require __DIR__ . '/includes/footer.php'; ?>
