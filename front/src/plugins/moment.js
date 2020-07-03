import Vue from "vue"

const moment = require("moment-timezone")
moment.locale("ru")
moment.tz.setDefault("Europe/Moscow")

Vue.prototype.$moment = moment
