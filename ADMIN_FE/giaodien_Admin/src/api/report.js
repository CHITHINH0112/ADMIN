//report.js
import { order } from "./order";

export const report = {
  summary: async () => {
    const res = await order.list();
    const orders = res.data || [];
    return {
      totalOrders: orders.length,
      totalRevenue: orders.reduce((s, o) => s + Number(o.total || 0), 0),
    };
  },
};
