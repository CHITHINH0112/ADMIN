//auth.js
import axiosClient from "./axios";

export const auth = {
  login: (username, password) =>
    axiosClient.post("auth/login", { username, password }),

  check: () =>
    axiosClient.get("auth/checkSession"),

  logout: () =>
    axiosClient.post("auth/logout"),
};
