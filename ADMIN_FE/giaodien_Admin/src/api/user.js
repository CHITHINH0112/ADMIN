// //user.js
// import axiosClient from "./axios";

// export const user = {
//   list: () => axiosClient.get("user/index"),
//   getById: (id) => axiosClient.get(`user/id/${id}`),
//   update: (id, data) => axiosClient.post(`user/update/${id}`, data),
//   delete: (id) => axiosClient.post(`user/delete/${id}`),
// };
///////////////
import axiosClient from "./axios";

export const user = {
  list: () => axiosClient.get("user/index"),
  delete: (id) => axiosClient.post(`user/delete/${id}`),
  changeStatus: (id, status) =>
    axiosClient.post(`user/status/${id}`, { status }),
resetPassword: (id) =>
  axiosClient.post(`user/resetPassword/${id}`),

};
