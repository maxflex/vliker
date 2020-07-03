<template>
  <div class="page-go">
    <div style="width: 100%">
      <Bar :value="currentTask.url" />
    </div>
    <TaskWrapper />
    <div class="controls">
      <v-button class="flex-items" @click.native="stop">
        <i class="material-icons mr-2">stop</i>
        <span>завершить накрутку</span>
      </v-button>
    </div>
  </div>
</template>

<script>
// import VueOdometer from 'v-odometer/src'
import Bar from "@/components/Element/Bar"
import TaskWrapper from "@/components/Task/Wrapper"
import { mapActions, mapState } from "vuex"
import store from "@/store"

export default {
  components: { Bar, TaskWrapper },

  data() {
    return {}
  },

  beforeRouteEnter(to, from, next) {
    if (store.state.task.currentTask === null) {
      return next("/")
    }
    next()
  },

  methods: {
    ...mapActions("task", ["stop"]),
  },

  computed: {
    ...mapState("task", ["currentTask"]),
  },
}
</script>

<style lang="scss" scoped>
@import "@/scss/_variables";

.page-go {
  align-items: center;
  display: flex;
  flex-direction: column;
  justify-content: space-between;
}

.controls {
  display: flex;
}
</style>
