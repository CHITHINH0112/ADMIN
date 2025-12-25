<?php
require_once "./core/database.php";

class ProductVariant
{
    private $con;

    public function __construct()
    {
        $this->con = (new Database())->connect();
    }

    // ===============================
    // CHECK EXIST
    // ===============================
    public function exists($product_id, $color_id, $size_id)
    {
        $stmt = $this->con->prepare(
            "SELECT id FROM product_variants 
             WHERE product_id=? AND color_id=? AND size_id=?"
        );
        $stmt->execute([$product_id, $color_id, $size_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // ===============================
    // INSERT VARIANT
    // ===============================
    public function insert($product_id, $color_id, $size_id, $price, $stock)
    {
        if ($price < 0 || $stock < 0) {
            throw new Exception("Giá hoặc tồn kho không hợp lệ");
        }

        $stmt = $this->con->prepare(
            "INSERT INTO product_variants
            (product_id, color_id, size_id, price, stock)
            VALUES (?, ?, ?, ?, ?)"
        );

        $stmt->execute([
            $product_id,
            $color_id,
            $size_id,
            $price,
            $stock
        ]);

        return $this->con->lastInsertId();
    }

    // ===============================
    // UPDATE VARIANT
    // ===============================
    public function update($id, $color_id, $size_id, $price, $stock)
    {
        if ($price < 0 || $stock < 0) {
            throw new Exception("Giá hoặc tồn kho không hợp lệ");
        }

        $stmt = $this->con->prepare(
            "UPDATE product_variants
             SET color_id=?, size_id=?, price=?, stock=?
             WHERE id=?"
        );

        return $stmt->execute([
            $color_id,
            $size_id,
            $price,
            $stock,
            $id
        ]);
    }

    // ===============================
    // DELETE VARIANTS NOT IN LIST
    // ===============================
    public function deleteNotIn($product_id, $ids)
    {
        if (empty($ids)) {
            return;
        }

        $placeholders = implode(',', array_fill(0, count($ids), '?'));

        $sql = "DELETE FROM product_variants
                WHERE product_id = ?
                AND id NOT IN ($placeholders)";

        $stmt = $this->con->prepare($sql);
        $stmt->execute(array_merge([$product_id], $ids));
    }

public function insertImage($variant_id, $path)
{
    $stmt = $this->con->prepare(
        "INSERT INTO product_images (variant_id, image_path)
         VALUES (?, ?)"
    );
    return $stmt->execute([$variant_id, $path]);
}



    // ===============================
    // INSERT VARIANT IMAGE ⭐
    // ===============================
    // public function insertImage($variant_id, $image_path)
    // {
    //     $stmt = $this->con->prepare(
    //         "INSERT INTO product_images (variant_id, image_path)
    //          VALUES (?, ?)"
    //     );

    //     return $stmt->execute([
    //         $variant_id,
    //         $image_path
    //     ]);
    // }
}
