export default {
  SET_USER(store, user) {
    store.user = user
  },
  SET_HAS_TASKS(store, hasTasks) {
    store.user.has_tasks = hasTasks
  },
  SET_NEW_NOTIFICATIONS(store, newNotifications) {
    store.user.new_notifications = newNotifications
  },
}
