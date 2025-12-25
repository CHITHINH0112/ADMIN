import { useState } from "react";
import { useNavigate } from "react-router-dom";
import { auth } from "../api/auth";

export default function AdminLogin() {
  const [username, setUsername] = useState("");
  const [password, setPassword] = useState("");
  const [error, setError] = useState("");
  const nav = useNavigate();

  const submit = async (e) => {
    e.preventDefault();
    try {
      const res = await auth.login(username, password);
      console.log(res);
      if (res.data.status === "success" && res.data.user.role === "admin") {
        nav("/admin");
      } else setError("Không có quyền admin");
    } catch {
      setError("Sai tài khoản hoặc mật khẩu");
    }
  };

  return (
    <div className="min-h-screen flex items-center justify-center bg-gray-100">
      <form onSubmit={submit} className="bg-white p-6 rounded shadow w-80">
        <h2 className="text-xl font-bold mb-3">Admin Login</h2>
        {error && <p className="text-red-600">{error}</p>}
        <input className="border w-full p-2 mb-2" placeholder="Username" onChange={e=>setUsername(e.target.value)} />
        <input type="password" className="border w-full p-2 mb-2" placeholder="Password" onChange={e=>setPassword(e.target.value)} />
        <button className="bg-blue-600 text-white w-full py-2">Login</button>
      </form>
    </div>
  );
}

