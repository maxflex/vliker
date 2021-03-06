import { TASK_TYPE_META } from "@/components/Task"
import router from "@/router"
import http from "@/plugins/http"

const apiUrl = "tasks"

export default {
  start({ commit }, payload) {
    return http.post(apiUrl, payload).then(r => {
      commit("SET", r.data)
      router.push({ name: "GoPage" })
    })
  },

  stop({ commit, state }, showMessage = true) {
    if (showMessage) {
      commit(
        "message/SHOW",
        `<b>Вы накрутили
      <span class='text_red'>
        ${state.currentTask.actions_from_count}
      </span>
      ${TASK_TYPE_META[state.currentTask.type].label}
      </b>
      <hr />
      <span class='text_size-14 text_grey'>
        Убедитесь, что ваша страница открыта
      </span>
      `,
        { root: true },
      )
    }

    commit("SET", null)
    commit("auth/SET_HAS_TASKS", true, { root: true })
    router.push({ name: "StatsPage" })
  },

  get() {
    return http.get(apiUrl)
  },

  checkQueue(params, item) {
    return http.get([apiUrl, "check-queue", item.id].join("/"))
  },
}
