<?php
require_once("./app/Model/ProductModel.php");
require_once("./app/Model/ProductVariantModel.php");
require_once("./core/database.php");

require_once("./app/Model/ProductVariantModel.php");
require("./app/Middleware/AdminMiddleware.php");

class ProductService
{
    private $productModel;
    private $variantModel;

    public function __construct()
    {
        $this->productModel = new Product();
        $this->variantModel = new ProductVariant();
    }

    // =================
    // GET
    // =================
    public function getAll()
    {
        return $this->productModel->getAll();
    }

    public function search($keyword)
{
    return $this->productModel->search($keyword);
}

    public function getById($id)
    {
        return $this->productModel->getById($id);
    }
 
    // =================
    // CREATE
    // =================

public function create($data, $files)
{
    AdminMiddleware::requireAdmin();

    if (empty($data['name']) || empty($data['subcategory_id'])) {
        return ["error" => "Thiếu thông tin sản phẩm"];
    }

    $productId = $this->productModel->insert(
        $data['name'],
        $data['description'] ?? '',
        $data['subcategory_id'],
        $data['status'] ?? 'available',
        null
    );

    if (!$productId) {
        return ["error" => "Không tạo được sản phẩm"];
    }

    if (!empty($data['variants'])) {
        foreach ($data['variants'] as $i => $v) {
            $variantId = $this->variantModel->insert(
                $productId,
                $v['color_id'],
                $v['size_id'],
                $v['price'],
                $v['stock']
            );

            // upload ảnh variant
            if (!empty($files['variant_images']['tmp_name'][$i])) {$fileName = time() . "_" . preg_replace('/\s+/', '_', $files['variant_images']['name'][$i]);

$uploadDir = __DIR__ . "/../../uploads/variants/";
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$fullPath = $uploadDir . $fileName;
$publicPath = "/uploads/variants/" . $fileName;

move_uploaded_file(
    $files['variant_images']['tmp_name'][$i],
    $fullPath
);

$this->variantModel->insertImage($variantId, $publicPath);


// LƯU PATH PUBLIC (KHÔNG CÓ public/)
$this->variantModel->insertImage($variantId, $publicPath);

            }
        }
    }

    return ["status" => "success"];
}


    // =================
    // UPDATE
    // =================
    public function update($data)
    {
        AdminMiddleware::requireAdmin();

        $this->productModel->update(
            $data['id'],
            $data['name'],
            $data['description'] ?? '',
            $data['subcategory_id'] ?? 2,
            $data['status'] ?? 'available',
            $data['image'] ?? null
        );

        if (!empty($data['variants'])) {
            foreach ($data['variants'] as $v) {
                if (!empty($v['id'])) {
                    $this->variantModel->update(
                        $v['id'],
                        $v['color_id'],
                        $v['size_id'],
                        $v['price'],
                        $v['stock']
                    );
                } else {
                    $this->variantModel->insert(
                        $data['id'],
                        $v['color_id'],
                        $v['size_id'],
                        $v['price'],
                        $v['stock']
                    );
                }
            }
        }

        return ["status" => "success"];
    }

    // =================
    // DELETE
    // =================
    public function delete($id)
    {
        AdminMiddleware::requireAdmin();
        return $this->productModel->delete($id)
            ? ["status" => "success"]
            : ["error" => "Delete failed"];
    }
}
