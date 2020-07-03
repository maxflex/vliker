import Cookies from "js-cookie"
import http from "@/plugins/http"
const apiUrl = "users"
const apiTokenField = "api_token"

export default {
  createUser({ commit }) {
    console.log("overhere")
    return http.post(apiUrl).then(r => {
      const user = r.data
      console.log("user", user)
      Cookies.set(apiTokenField, user.api_token, {
        expires: 60,
      })
      commit("SET_USER", user)
    })
  },

  login({ commit }) {
    return http.get(apiUrl).then(r => {
      commit("SET_USER", r.data)
    })
  },
}
