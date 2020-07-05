import Vue from "vue"
import App from "./App.vue"
import store from "./store"
import router from "./router"
import http from "@/plugins/http"
require("@/plugins/filters")
require("@/plugins/directives")
// require("@/plugins/moment")
import VuePageTransition from "vue-page-transition"
import { upperFirst, camelCase } from "lodash"
import Cookies from "js-cookie"
require("@/plugins/directives")
require("@/scss/style.scss")

// Require @/components/UI globally
// https://vuejs.org/v2/guide/components-registration.html#Automatic-Global-Registration-of-Base-Components
const requireComponent = require.context(
  "@/components/UI",
  // Whether or not to look in subfolders
  false,
)

requireComponent.keys().forEach(fileName => {
  // Get component config
  const componentConfig = requireComponent(fileName)

  const componentName = upperFirst(
    camelCase(
      // Gets the file name regardless of folder depth
      fileName
        .split("/")
        .pop()
        .replace(/\.\w+$/, ""),
    ),
  )

  Vue.component(componentName, componentConfig.default || componentConfig)
})

http.interceptors.request.use(
  config => {
    const token = Cookies.get("api_token")
    if (token !== undefined) {
      config.headers.Authorization = `Bearer ${token}`
    } else {
      config.headers.Authorization = ""
    }
    return config
  },
  error => Promise.reject(error),
)

Vue.config.productionTip = false
Vue.prototype.$http = http
Vue.use(VuePageTransition)

new Vue({
  render: h => h(App),
  store,
  router,
}).$mount("#app")
