//src/admin/pages/Reports.jsx
import { useEffect, useState } from "react";
import { report } from "../../api/report";

export default function Reports() {
  const [data, setData] = useState({});
  useEffect(() => {
    report.summary().then(setData);
  }, []);
  return <pre>{JSON.stringify(data, null, 2)}</pre>;
}
