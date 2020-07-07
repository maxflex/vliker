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

  stop({ commit, state, dispatch }) {
    commit(
      "message/SHOW",
      `<span class='text_medium'>Вы накрутили
      <span class='text_red'>
        ${state.currentTask.actions_from_count}
      </span>
      ${TASK_TYPE_META[state.currentTask.type].label}
      </span>
      <hr />
      Убедитесь, что страница открыта`,
      { root: true },
    )

    commit("SET", null)

    // заново получаем пользователя, чтобы появилось
    // has_tasks=true и notifications_count
    dispatch("auth/login", null, { root: true })

    router.push({ name: "StatsPage" })
  },

  get() {
    return http.get(apiUrl)
  },

  checkQueue(params, item) {
    return http.get([apiUrl, "check-queue", item.id].join("/"))
  },
}
