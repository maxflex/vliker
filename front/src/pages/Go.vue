<template>
  <div class="page-go">
    <div style="width: 100%">
      <Bar :value="currentTask.url" />
    </div>
    <TaskWrapper />
    <div class="controls">
      <v-button class="flex-items" @click.native="stop()">
        <i class="material-icons mr-2">stop</i>
        <span class="vertical-fix">завершить накрутку</span>
        <!-- <i class="material-icons ml-2">
          keyboard_arrow_right
        </i> -->
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
    document.getElementById("vk_community_messages").classList.add("d-none")
    next()
  },

  beforeRouteLeave(to, from, next) {
    document.getElementById("vk_community_messages").classList.remove("d-none")
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
  justify-content: space-evenly;
  width: 100%;
  margin-bottom: 18px;
}
</style>
