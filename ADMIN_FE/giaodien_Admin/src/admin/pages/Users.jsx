
// ////////////////
// import { useEffect, useState } from "react";
// import { user } from "../../api/user";

// export default function Users() {
//   const [users, setUsers] = useState([]);
//   const [search, setSearch] = useState("");

//   // PHÂN TRANG
//   const [currentPage, setCurrentPage] = useState(1);
//   const usersPerPage = 5;

//   const loadUsers = () => {
//     user.list().then(res => {
//       if (Array.isArray(res.data)) setUsers(res.data);
//     });
//   };

//   useEffect(() => {
//     loadUsers();
//   }, []);

//   const handleDelete = (u) => {
//     if (u.role === "admin") {
//       alert("Không được xóa admin");
//       return;
//     }
//     if (confirm("Xóa user này?")) {
//       user.delete(u.id).then(loadUsers);
//     }
//   };

//   const toggleStatus = (u) => {
//     const newStatus = u.status === "active" ? "banned" : "active";

//     user.changeStatus(u.id, newStatus)
//       .then(() => loadUsers())
//       .catch(() => alert("Không đổi được trạng thái"));
//   };

//   // SEARCH
//   const filteredUsers = users.filter(u =>
//     u.username.toLowerCase().includes(search.toLowerCase()) ||
//     u.email.toLowerCase().includes(search.toLowerCase())
//   );

//   // PAGINATION LOGIC
//   const totalPages = Math.ceil(filteredUsers.length / usersPerPage);
//   const indexOfLast = currentPage * usersPerPage;
//   const indexOfFirst = indexOfLast - usersPerPage;
//   const currentUsers = filteredUsers.slice(indexOfFirst, indexOfLast);

//   return (
//     <div className="p-6 bg-white rounded shadow">
//       <h1 className="text-2xl font-bold mb-4">Quản lý người dùng</h1>

//       {/* SEARCH */}
//       <div className="mb-4">
//         <input
//           type="text"
//           placeholder="Tìm username hoặc email..."
//           className="border px-3 py-2 w-1/3 rounded focus:outline-none focus:ring"
//           value={search}
//           onChange={(e) => {
//             setSearch(e.target.value);
//             setCurrentPage(1);
//           }}
//         />
//       </div>

//       {/* TABLE */}
//       <table className="w-full border border-gray-300 border-collapse">
//         <thead className="bg-gray-100">
//           <tr>
//             <th className="border px-3 py-2">ID</th>
//             <th className="border px-3 py-2">Username</th>
//             <th className="border px-3 py-2">Email</th>
//             <th className="border px-3 py-2">Role</th>
//             <th className="border px-3 py-2">Status</th>
//             <th className="border px-3 py-2 text-left">Action</th>
//           </tr>
//         </thead>

//         <tbody>
//           {currentUsers.map(u => (
//             <tr
//               key={u.id}
//               className="hover:bg-gray-50 text-center"
//             >
//               <td className="border py-2">{u.id}</td>
//               <td className="border py-2">{u.username}</td>
//               <td className="border py-2">{u.email}</td>
//               <td className="border py-2">
//                 <span
//                   className={`px-2 py-1 rounded text-white text-sm ${
//                     u.role === "admin" ? "bg-red-500" : "bg-blue-500"
//                   }`}
//                 >
//                   {u.role}
//                 </span>
//               </td>
//               <td className="border py-2">
//                 <span
//                   className={`px-2 py-1 rounded text-white text-sm ${
//                     u.status === "active" ? "bg-green-500" : "bg-gray-500"
//                   }`}
//                 >
//                   {u.status}
//                 </span>
//               </td>
//               <td className="border py-2 text-left">
//                 <div className="flex gap-2">
//                   <button
//                     onClick={() => toggleStatus(u)}
//                     className="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm"
//                   >
//                     {u.status === "active" ? "Khóa" : "Mở"}
//                   </button>

//                   {u.role !== "admin" && (
//                     <button
//                       onClick={() => handleDelete(u)}
//                       className="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm"
//                     >
//                       Xóa
//                     </button>
//                   )}
//                 </div>
//               </td>
//             </tr>
//           ))}

//           {currentUsers.length === 0 && (
//             <tr>
//               <td colSpan="6" className="text-center py-4 text-gray-500">
//                 Không có user
//               </td>
//             </tr>
//           )}
//         </tbody>
//       </table>

//       {/* PAGINATION */}
//       <div className="flex justify-center items-center gap-4 mt-4">
//         <button
//           disabled={currentPage === 1}
//           onClick={() => setCurrentPage(currentPage - 1)}
//           className="px-3 py-1 border rounded disabled:opacity-50"
//         >
//           ◀ Trước
//         </button>

//         <span className="font-medium">
//           Trang {currentPage} / {totalPages || 1}
//         </span>

//         <button
//           disabled={currentPage === totalPages || totalPages === 0}
//           onClick={() => setCurrentPage(currentPage + 1)}
//           className="px-3 py-1 border rounded disabled:opacity-50"
//         >
//           Sau ▶
//         </button>
//       </div>
//     </div>
//   );
// }
////////////////////////
import { useEffect, useState } from "react";
import { user } from "../../api/user";

export default function Users() {
  const [users, setUsers] = useState([]);
  const [search, setSearch] = useState("");

  // phân trang
  const [currentPage, setCurrentPage] = useState(1);
  const usersPerPage = 5;

  // modal xem chi tiết
  const [selectedUser, setSelectedUser] = useState(null);

  const loadUsers = () => {
    user.list().then(res => {
      if (Array.isArray(res.data)) setUsers(res.data);
    });
  };

  useEffect(() => {
    loadUsers();
  }, []);

  const handleDelete = (u) => {
    if (u.role === "admin") {
      alert("Không được xóa admin");
      return;
    }
    if (confirm("Xóa user này?")) {
      user.delete(u.id).then(loadUsers);
    }
  };

  const toggleStatus = (u) => {
    const newStatus = u.status === "active" ? "banned" : "active";
    user.changeStatus(u.id, newStatus)
      .then(loadUsers)
      .catch(() => alert("Không đổi được trạng thái"));
  };

  const handleResetPassword = (u) => {
    if (confirm("Reset mật khẩu user về 123456?")) {
      user.resetPassword(u.id).then(() => {
        alert("Đã reset mật khẩu");
      });
    }
  };

  // search
  const filteredUsers = users.filter(u =>
    u.username.toLowerCase().includes(search.toLowerCase()) ||
    u.email.toLowerCase().includes(search.toLowerCase())
  );

  // pagination
  const totalPages = Math.ceil(filteredUsers.length / usersPerPage);
  const indexOfLast = currentPage * usersPerPage;
  const indexOfFirst = indexOfLast - usersPerPage;
  const currentUsers = filteredUsers.slice(indexOfFirst, indexOfLast);

  return (
    <div className="p-6 bg-white rounded shadow">
      <h1 className="text-2xl font-bold mb-4">Quản lý người dùng</h1>

      {/* SEARCH */}
      <div className="mb-4">
        <input
          type="text"
          placeholder="Tìm username hoặc email..."
          className="border px-3 py-2 w-1/3 rounded focus:outline-none focus:ring"
          value={search}
          onChange={(e) => {
            setSearch(e.target.value);
            setCurrentPage(1);
          }}
        />
      </div>

      {/* TABLE */}
      <table className="w-full border border-gray-300 border-collapse">
        <thead className="bg-gray-100">
          <tr>
            <th className="border px-3 py-2">ID</th>
            <th className="border px-3 py-2">Username</th>
            <th className="border px-3 py-2">Email</th>
            <th className="border px-3 py-2">Role</th>
            <th className="border px-3 py-2">Status</th>
            <th className="border px-3 py-2">Đơn gần nhất</th>
            <th className="border px-3 py-2 text-left">Action</th>
          </tr>
        </thead>

        <tbody>
          {currentUsers.map(u => (
            <tr key={u.id} className="hover:bg-gray-50 text-center">
              <td className="border py-2">{u.id}</td>
              <td className="border py-2">{u.username}</td>
              <td className="border py-2">{u.email}</td>
              <td className="border py-2">
                <span className={`px-2 py-1 rounded text-white text-sm ${
                  u.role === "admin" ? "bg-red-500" : "bg-blue-500"
                }`}>
                  {u.role}
                </span>
              </td>
              <td className="border py-2">
                <span className={`px-2 py-1 rounded text-white text-sm ${
                  u.status === "active" ? "bg-green-500" : "bg-gray-500"
                }`}>
                  {u.status}
                </span>
              </td>
              <td className="border py-2 text-sm">
                {u.last_order_at
                  ? new Date(u.last_order_at).toLocaleString()
                  : "Chưa có"}
              </td>
              <td className="border py-2 text-left">
                <div className="flex flex-wrap gap-2">
                  <button
                    onClick={() => toggleStatus(u)}
                    className="bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded text-sm"
                  >
                    {u.status === "active" ? "Khóa" : "Mở"}
                  </button>

                  <button
                    onClick={() => setSelectedUser(u)}
                    className="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm"
                  >
                    Xem
                  </button>

                  <button
                    onClick={() => handleResetPassword(u)}
                    className="bg-purple-500 hover:bg-purple-600 text-white px-3 py-1 rounded text-sm"
                  >
                    Reset pass
                  </button>

                  {u.role !== "admin" && (
                    <button
                      onClick={() => handleDelete(u)}
                      className="bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded text-sm"
                    >
                      Xóa
                    </button>
                  )}
                </div>
              </td>
            </tr>
          ))}

          {currentUsers.length === 0 && (
            <tr>
              <td colSpan="7" className="text-center py-4 text-gray-500">
                Không có user
              </td>
            </tr>
          )}
        </tbody>
      </table>

      {/* PAGINATION */}
      <div className="flex justify-center items-center gap-4 mt-4">
        <button
          disabled={currentPage === 1}
          onClick={() => setCurrentPage(currentPage - 1)}
          className="px-3 py-1 border rounded disabled:opacity-50"
        >
          ◀ Trước
        </button>

        <span className="font-medium">
          Trang {currentPage} / {totalPages || 1}
        </span>

        <button
          disabled={currentPage === totalPages || totalPages === 0}
          onClick={() => setCurrentPage(currentPage + 1)}
          className="px-3 py-1 border rounded disabled:opacity-50"
        >
          Sau ▶
        </button>
      </div>

      {/* MODAL CHI TIẾT */}
      {selectedUser && (
        <div className="fixed inset-0 bg-black bg-opacity-40 flex justify-center items-center z-50">
          <div className="bg-white p-6 rounded w-96">
            <h2 className="text-xl font-bold mb-4">Chi tiết user</h2>

            <p><b>ID:</b> {selectedUser.id}</p>
            <p><b>Username:</b> {selectedUser.username}</p>
            <p><b>Email:</b> {selectedUser.email}</p>
            <p><b>Role:</b> {selectedUser.role}</p>
            <p><b>Status:</b> {selectedUser.status}</p>
            <p>
              <b>Đơn gần nhất:</b>{" "}
              {selectedUser.last_order_at
                ? new Date(selectedUser.last_order_at).toLocaleString()
                : "Chưa có"}
            </p>

            <div className="text-right mt-4">
              <button
                onClick={() => setSelectedUser(null)}
                className="px-4 py-2 bg-gray-500 text-white rounded"
              >
                Đóng
              </button>
            </div>
          </div>
        </div>
      )}
    </div>
  );
}
