//orders.js
import axiosClient from "./axios";

export const order = {
  list: () => axiosClient.get("order/index"),
};
