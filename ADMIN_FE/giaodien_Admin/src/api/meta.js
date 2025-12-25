//meta.js
import axiosClient from "./axios";

export const meta = {
  categories: () => axiosClient.get("meta/categories"),
  subcategories: (id) => axiosClient.get(`meta/subcategories/${id}`),
  allSubcategories: () => axiosClient.get("meta/allSubcategories"),

  colors: () => axiosClient.get("meta/colors"),
  sizes: () => axiosClient.get("meta/sizes"),
};
