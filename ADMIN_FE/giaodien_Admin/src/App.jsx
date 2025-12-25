// import { Routes, Route } from "react-router-dom";
// import AdminLogin from "./admin/AdminLogin";
// import AdminLayout from "./admin/AdminLayout";
// import Dashboard from "./admin/pages/Dashboard";
// import Products from "./admin/pages/Products";
// import Users from "./admin/pages/Users";
// import Orders from "./admin/pages/Orders";
// import Reports from "./admin/pages/Reports";

// export default function App() {
//   return (
//     <Routes>
//       <Route path="/login" element={<AdminLogin />} />

//       <Route path="/admin" element={<AdminLayout />}>
//         <Route index element={<Dashboard />} />
//         <Route path="products" element={<Products />} />
//         <Route path="users" element={<Users />} />
//         <Route path="orders" element={<Orders />} />
//         <Route path="reports" element={<Reports />} />
//       </Route>
//     </Routes>
//   );
// }
/////////////////////////
import { Routes, Route, Navigate } from "react-router-dom";
import AdminLogin from "./admin/AdminLogin";
import AdminLayout from "./admin/AdminLayout";
import Dashboard from "./admin/pages/Dashboard";
import Products from "./admin/pages/Products";
import Users from "./admin/pages/Users";
import Orders from "./admin/pages/Orders";
import Reports from "./admin/pages/Reports";

export default function App() {
  return (
    <Routes>
      {/* AUTO vào login */}
      <Route path="/" element={<Navigate to="/login" replace />} />

      <Route path="/login" element={<AdminLogin />} />

      {/* ADMIN – KHÔNG GUARD */}
      <Route path="/admin" element={<AdminLayout />}>
        <Route index element={<Dashboard />} />
        <Route path="products" element={<Products />} />
        <Route path="users" element={<Users />} />
        <Route path="orders" element={<Orders />} />
        <Route path="reports" element={<Reports />} />
      </Route>
    </Routes>
  );
}
