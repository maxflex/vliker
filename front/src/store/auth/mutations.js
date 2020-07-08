export default {
  SET_USER(store, user) {
    store.user = user
  },
  SET_HAS_TASKS(store, hasTasks) {
    store.user.has_tasks = hasTasks
  },
  SET_NOTIFICATIONS_COUNT(store, notificationsCount) {
    store.user.notifications_count = notificationsCount
  },
}
