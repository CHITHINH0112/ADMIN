
/////////////////////////////
import axiosClient from "./axios";

export const productApi = {
  // =====================
  // GET LIST
  // =====================
  list: () => axiosClient.get("product/index"),

  // =====================
  // SEARCH (THEO TÊN)
  // =====================
  search: (keyword) =>
    axiosClient.get("product/search", {
      params: { keyword },
    }),

  get: (id) => axiosClient.get(`product/getid/${id}`),

  // =====================
  // CREATE
  // =====================
  create: (formData) =>
    axiosClient.post("product/create", formData, {
      headers: {
        "Content-Type": "multipart/form-data",
      },
    }),

  // =====================
  // UPDATE
  // =====================
  update: (formData) =>
    axiosClient.post("product/update", formData, {
      headers: {
        "Content-Type": "multipart/form-data",
      },
    }),

  // =====================
  // DELETE
  // =====================
  delete: (id) =>
    axiosClient.post(`product/delete/${id}`),
};
