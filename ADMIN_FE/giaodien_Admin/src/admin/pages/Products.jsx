import { useEffect, useState } from "react";
import { productApi } from "../../api/product";
import { meta } from "../../api/meta";

export default function Products() {
  // ================= STATE =================
  const [products, setProducts] = useState([]);
  const [keyword, setKeyword] = useState("");

  const [subcategories, setSubcategories] = useState([]);
  const [colors, setColors] = useState([]);
  const [sizes, setSizes] = useState([]);

  const [editingId, setEditingId] = useState(null);

  const [name, setName] = useState("");
  const [description, setDescription] = useState("");
  const [subcategoryId, setSubcategoryId] = useState("");

  const [variants, setVariants] = useState([]);

  // ================= LOAD =================
  const loadProducts = async (kw = "") => {
    console.log("KEYWORD SENT:", kw);
    const res = kw
      ? await productApi.search(kw)
      : await productApi.list();

      console.log("SEARCH RESULT:", res.data);
    setProducts(Array.isArray(res.data) ? res.data : []);
  };

  useEffect(() => {
    loadProducts();
    meta.allSubcategories().then(r => setSubcategories(r.data || []));
    meta.colors().then(r => setColors(r.data || []));
    meta.sizes().then(r => setSizes(r.data || []));
  }, []);

  // ================= VARIANTS =================
  const addVariant = () => {
    setVariants([
      ...variants,
      {
        color_id: "",
        size_id: "",
        price: "",
        stock: "",
        image: null,
        preview: null,
      },
    ]);
  };

  const updateVariant = (i, field, value) => {
    const copy = [...variants];
    copy[i][field] = value;
    setVariants(copy);
  };

  const removeVariant = (i) => {
    setVariants(variants.filter((_, idx) => idx !== i));
  };

  // ================= RESET =================
  const resetForm = () => {
    setEditingId(null);
    setName("");
    setDescription("");
    setSubcategoryId("");
    setVariants([]);
  };

  // ================= CREATE =================
  const handleCreate = async () => {
    if (!name || !subcategoryId) {
      alert("Thiếu tên sản phẩm hoặc danh mục");
      return;
    }

    const form = new FormData();
    form.append("name", name);
    form.append("description", description);
    form.append("subcategory_id", subcategoryId);
    form.append("status", "available");

    variants.forEach((v, i) => {
      form.append(`variants[${i}][color_id]`, v.color_id);
      form.append(`variants[${i}][size_id]`, v.size_id);
      form.append(`variants[${i}][price]`, v.price);
      form.append(`variants[${i}][stock]`, v.stock);
      if (v.image) form.append(`variant_images[${i}]`, v.image);
    });

    await productApi.create(form);
    resetForm();
    loadProducts();
  };

  // ================= EDIT =================
  const handleEdit = async (id) => {
    const res = await productApi.get(id);
    const p = res.data;

    setEditingId(p.id);
    setName(p.name);
    setDescription(p.description || "");
    setSubcategoryId(p.subcategory_id);

    setVariants(
      (p.variants || []).map(v => ({
        id: v.variant_id,
        color_id: v.color_id,
        size_id: v.size_id,
        price: v.price,
        stock: v.stock,
        image: null,
        preview: v.image_path
          ? `http://localhost:8000${v.image_path}`
          : null,
      }))
    );
  };

  // ================= UPDATE =================
  const handleUpdate = async () => {
    const form = new FormData();
    form.append("id", editingId);
    form.append("name", name);
    form.append("description", description);
    form.append("subcategory_id", subcategoryId);
    form.append("status", "available");

    variants.forEach((v, i) => {
      if (v.id) form.append(`variants[${i}][id]`, v.id);
      form.append(`variants[${i}][color_id]`, v.color_id);
      form.append(`variants[${i}][size_id]`, v.size_id);
      form.append(`variants[${i}][price]`, v.price);
      form.append(`variants[${i}][stock]`, v.stock);
      if (v.image) form.append(`variant_images[${i}]`, v.image);
    });

    await productApi.update(form);
    resetForm();
    loadProducts();
  };

  // ================= DELETE =================
  const handleDelete = async (id) => {
    if (!window.confirm("Xóa sản phẩm này?")) return;
    await productApi.delete(id);
    loadProducts();
  };

  // ================= RENDER =================
  return (
    <div className="p-6">
      <h2 className="text-2xl font-bold mb-6">Quản lý sản phẩm</h2>

      {/* SEARCH */}
      <div className="flex gap-2 mb-6">
        <input
          className="border rounded px-3 py-2 flex-1"
          placeholder="Tìm theo tên sản phẩm"
          value={keyword}
          onChange={e => setKeyword(e.target.value)}
        />
        <button
          className="bg-black text-white px-6 rounded"
          onClick={() => loadProducts(keyword)}
        >
          Search
        </button>
      </div>

      {/* FORM */}
      <div className="border rounded-xl p-5 mb-8 bg-gray-50">
        <h3 className="font-semibold text-lg mb-4">
          {editingId ? "Sửa sản phẩm" : "Thêm sản phẩm"}
        </h3>

        <input
          className="border rounded p-2 w-full mb-3"
          placeholder="Tên sản phẩm"
          value={name}
          onChange={e => setName(e.target.value)}
        />

        <textarea
          className="border rounded p-2 w-full mb-3"
          placeholder="Mô tả"
          value={description}
          onChange={e => setDescription(e.target.value)}
        />

        <select
          className="border rounded p-2 w-full mb-4"
          value={subcategoryId}
          onChange={e => setSubcategoryId(e.target.value)}
        >
          <option value="">-- Chọn danh mục con --</option>
          {subcategories.map(s => (
            <option key={s.id} value={s.id}>{s.name}</option>
          ))}
        </select>

        {/* VARIANTS */}
        {variants.map((v, i) => (
          <div
            key={i}
            className="border rounded-lg p-4 mb-4 bg-white shadow-sm"
          >
            <div className="grid grid-cols-2 gap-2 mb-2">
              <select
                className="border p-2 rounded"
                value={v.color_id}
                onChange={e => updateVariant(i, "color_id", e.target.value)}
              >
                <option value="">Màu</option>
                {colors.map(c => (
                  <option key={c.id} value={c.id}>{c.name}</option>
                ))}
              </select>

              <select
                className="border p-2 rounded"
                value={v.size_id}
                onChange={e => updateVariant(i, "size_id", e.target.value)}
              >
                <option value="">Size</option>
                {sizes.map(s => (
                  <option key={s.id} value={s.id}>{s.name}</option>
                ))}
              </select>
            </div>

            <div className="grid grid-cols-2 gap-2 mb-2">
              <input
                className="border p-2 rounded"
                placeholder="Giá"
                value={v.price}
                onChange={e => updateVariant(i, "price", e.target.value)}
              />
              <input
                className="border p-2 rounded"
                placeholder="Tồn kho"
                value={v.stock}
                onChange={e => updateVariant(i, "stock", e.target.value)}
              />
            </div>

            <input
              type="file"
              accept="image/*"
              onChange={(e) => {
                const file = e.target.files[0];
                if (!file) return;
                const copy = [...variants];
                copy[i].image = file;
                copy[i].preview = URL.createObjectURL(file);
                setVariants(copy);
              }}
            />

            {v.preview && (
              <img
                src={v.preview}
                alt="preview"
                className="mt-3 w-28 h-28 object-cover rounded border"
              />
            )}

            <button
              className="text-red-600 mt-3"
              onClick={() => removeVariant(i)}
            >
              Xóa variant
            </button>
          </div>
        ))}

        <div className="flex gap-3">
          <button
            className="border px-4 py-1 rounded"
            onClick={addVariant}
          >
            + Thêm variant
          </button>
          <button
            className="bg-green-600 text-white px-6 py-1 rounded"
            onClick={editingId ? handleUpdate : handleCreate}
          >
            {editingId ? "Cập nhật" : "Lưu sản phẩm"}
          </button>
        </div>
      </div>

      {/* LIST */}
      <h3 className="font-semibold text-lg mb-4">Danh sách sản phẩm</h3>

      {products.map(p => (
        <div
          key={p.id}
          className="border rounded-xl p-4 mb-3 flex items-center gap-4 shadow hover:shadow-md transition"
        >
          <img
            src={
              p.thumbnail
                ? `http://localhost:8000${p.thumbnail}`
                : "http://localhost:8000/uploads/no-image.png"
            }
            alt={p.name}
            className="w-20 h-20 object-cover rounded border"
          />

          <div className="flex-1">
            <div className="font-semibold text-lg">{p.name}</div>
            <div className="text-sm text-gray-500 line-clamp-2">
              {p.description}
            </div>
          </div>

          <div className="flex gap-2">
            <button
              onClick={() => handleEdit(p.id)}
              className="bg-blue-500 hover:bg-blue-600 text-white px-4 py-1 rounded"
            >
              Sửa
            </button>
            <button
              onClick={() => handleDelete(p.id)}
              className="bg-red-500 hover:bg-red-600 text-white px-4 py-1 rounded"
            >
              Xóa
            </button>
          </div>
        </div>
      ))}
    </div>
  );
}
