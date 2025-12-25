<?php
require_once("./core/database.php");

class Product
{
    private $con;

    public function __construct()
    {
        $db = new Database();
        $this->con = $db->connect();
    }

    // GET ALL (KHÔNG JOIN)
    public function getAll()
    {
        $stmt = $this->con->prepare("
    SELECT 
        p.*,
        (
            SELECT pi.image_path
            FROM product_variants pv
            JOIN product_images pi ON pi.variant_id = pv.id
            WHERE pv.product_id = p.id
            LIMIT 1
        ) AS thumbnail
    FROM products p
    ORDER BY p.id DESC
");
        $stmt->execute();
        $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($products as &$p) {
            $p['variants'] = $this->getVariants($p['id']);
        }

        return $products;
    }

    public function getById($id)
    {
        $stmt = $this->con->prepare("SELECT * FROM products WHERE id = ?");
        $stmt->execute([$id]);
        $product = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($product) {
            $product['variants'] = $this->getVariants($id);
        }

        return $product;
    }

    public function insert($name, $description, $subcategory_id, $status, $image)
    {
        $stmt = $this->con->prepare(
            "INSERT INTO products (name, description, subcategory_id, status, image)
             VALUES (?, ?, ?, ?, ?)"
        );

        return $stmt->execute([
            $name,
            $description,
            $subcategory_id,
            $status,
            $image
        ]) ? $this->con->lastInsertId() : false;
    }

    public function update($id, $name, $description, $subcategory_id, $status, $image)
    {
        $stmt = $this->con->prepare(
            "UPDATE products
             SET name=?, description=?, subcategory_id=?, status=?, image=?
             WHERE id=?"
        );

        return $stmt->execute([
            $name,
            $description,
            $subcategory_id,
            $status,
            $image,
            $id
        ]);
    }

    public function delete($id)
    {
        $stmt = $this->con->prepare("DELETE FROM products WHERE id=?");
        return $stmt->execute([$id]);
    }

    private function getVariants($product_id)
    {
        $stmt = $this->con->prepare(
            "SELECT id AS variant_id, color_id, size_id, price, stock
             FROM product_variants
             WHERE product_id = ?"
        );
        $stmt->execute([$product_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    

 public function search($keyword = "")
{
    $stmt = $this->con->prepare(
        "SELECT * FROM products 
         WHERE name LIKE ?
         ORDER BY id DESC"
    );
    $stmt->execute(['%' . $keyword . '%']);
    $products = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($products as &$p) {
        // lấy variants
        $variants = $this->getVariants($p['id']);

        // gán variants
        $p['variants'] = $variants;

        // 🔥 GÁN THUMBNAIL
        $p['thumbnail'] = null;
        if (!empty($variants)) {
            $imgStmt = $this->con->prepare(
                "SELECT image_path 
                 FROM product_images 
                 WHERE variant_id = ? 
                 LIMIT 1"
            );
            $imgStmt->execute([$variants[0]['variant_id']]);
            $img = $imgStmt->fetch(PDO::FETCH_ASSOC);

            if ($img) {
                $p['thumbnail'] = $img['image_path'];
            }
        }
    }

    return $products;
}


}
