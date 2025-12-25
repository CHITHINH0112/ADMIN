import { useEffect, useState } from "react";
import { Outlet, Navigate } from "react-router-dom";
import { auth } from "../api/auth";
import Sidebar from "./Sidebar";

export default function AdminLayout() {
  const [ok, setOk] = useState(null);

  useEffect(() => {
    auth.check().then(res => {
      setOk(res.data.status === "success" && res.data.user.role === "admin");
    }).catch(() => setOk(false));
  }, []);

  if (ok === null) return <div className="p-6">Loading...</div>;
  if (!ok) return <Navigate to="/login" />;

  return (
    <div className="flex min-h-screen">
      <Sidebar />
      <main className="flex-1 p-6 bg-gray-50">
        <Outlet />
      </main>
    </div>
  );
}
