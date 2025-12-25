//src/admin/pages/Orders.jsx
import { useEffect, useState } from "react";
import { order } from "../../api/order";

export default function Orders() {
  const [data, setData] = useState([]);
  useEffect(() => {
    order.list().then(res => setData(res.data));
  }, []);
  return <pre>{JSON.stringify(data, null, 2)}</pre>;
}
