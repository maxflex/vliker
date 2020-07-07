import { isArray } from "lodash"

export default {
  SHOW(store, text) {
    store.text = isArray(text) ? text.join("<br />") : text
    store.isActive = true
  },
}
