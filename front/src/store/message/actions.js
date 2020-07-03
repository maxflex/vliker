import { isArray } from "lodash"

export default {
  set({ commit }, text) {
    if (isArray(text)) {
      text = text.join("<br />")
    }
    commit("SET", text)
  },
}
