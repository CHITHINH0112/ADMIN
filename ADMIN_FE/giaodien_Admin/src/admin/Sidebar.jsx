import { NavLink } from "react-router-dom";

const menu = [
  { to: "/admin", label: "Dashboard" },
  { to: "/admin/products", label: "Sản phẩm" },
  { to: "/admin/users", label: "Người dùng" },
  { to: "/admin/orders", label: "Đơn hàng" },
  { to: "/admin/reports", label: "Báo cáo" },
];

export default function Sidebar() {
  return (
    <aside className="w-56 bg-gray-800 text-white p-4 space-y-2">
      <h2 className="text-lg font-bold mb-4">ADMIN</h2>

      {menu.map((item) => (
        <NavLink
          key={item.to}
          to={item.to}
          end
          className={({ isActive }) =>
            `block px-3 py-2 rounded ${
              isActive
                ? "bg-gray-700 font-semibold"
                : "hover:bg-gray-700"
            }`
          }
        >
          {item.label}
        </NavLink>
      ))}
    </aside>
  );
}
