<template>
  <div class="pt-4">
    <div v-if="tasks !== null">
      <Item v-for="task in tasks" :item="task" :key="task.id" />
    </div>
  </div>
</template>

<script>
import { API_URL as USERS_API_URL } from "@/components/User"
import Item from "@/components/Stats/Item"
import { mapState } from "vuex"

export default {
  components: { Item },

  beforeRouteLeave(to, from, next) {
    this.$store.commit("auth/SET_NEW_NOTIFICATIONS", {})
    next()
  },

  data() {
    return {
      USERS_API_URL,
      tasks: null,
    }
  },

  created() {
    this.$store.dispatch("task/get").then(r => {
      this.tasks = r.data
    })
  },

  computed: {
    ...mapState("auth", ["user"]),
  },
}
</script>
