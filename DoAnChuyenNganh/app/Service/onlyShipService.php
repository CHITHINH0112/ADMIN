<?php


require_once("./app/Model/OnlyShipModel.php");
require_once("./app/Middleware/AdminMiddleware.php");

class OnlyShipService
{
    private $model;

    public function __construct()
    {
        $this->model = new OnlyShipModel();
    }

    public function getAll()
    {
        AdminMiddleware::requireAdmin();
        return $this->model->getAll();
    }

    public function insert($data)
    {
        AdminMiddleware::requireAdmin();

        if (!isset($data['name'], $data['price'])) {
            return ["error" => "Thiếu tên hoặc giá"];
        }

        $ok = $this->model->insert(
            $data['name'],
            $data['phone'] ?? null,
            $data['price']
        );

        return $ok
            ? ["message" => "Thêm đơn vị vận chuyển thành công"]
            : ["error" => "Thêm thất bại"];
    }

    public function update($data)
    {
        AdminMiddleware::requireAdmin();

        if (!isset($data['id'], $data['name'], $data['price'])) {
            return ["error" => "Thiếu dữ liệu"];
        }

        $ok = $this->model->update(
            $data['id'],
            $data['name'],
            $data['phone'] ?? null,
            $data['price']
        );

        return $ok
            ? ["message" => "Cập nhật thành công"]
            : ["error" => "Cập nhật thất bại"];
    }

    public function updatePrice($data)
    {
        AdminMiddleware::requireAdmin();

        if (!isset($data['id'], $data['price'])) {
            return ["error" => "Thiếu id hoặc giá"];
        }

        return $this->model->updatePrice($data['id'], $data['price'])
            ? ["message" => "Cập nhật giá ship thành công"]
            : ["error" => "Cập nhật thất bại"];
    }

    public function delete($id)
    {
        AdminMiddleware::requireAdmin();

        if ($this->model->hasOrders($id)) {
            return ["error" => "Không thể xóa – đã có đơn hàng"];
        }

        return $this->model->delete($id)
            ? ["message" => "Đã xóa đơn vị vận chuyển"]
            : ["error" => "Xóa thất bại"];
    }
}



