<template>
  <div>
    <div class="progress">
      <div
        v-if="currentTask.actions_from_count === 0"
        class="text-center"
        style="align-self: flex-end"
      >
        <span>нажмите, чтобы начать накрутку</span>
        <div class="arrow-down-animated">
          <i class="material-icons">arrow_downward</i>
        </div>
      </div>
      <div class="progress__label" v-else>
        <i class="material-icons progress__label__icon text_red mr-1">
          {{ TASK_TYPE_META[currentTask.type].icon }}
        </i>
        <span class="progress__label__counter text_red mr-2">
          <v-number :value="currentTask.actions_from_count"></v-number>
        </span>
        <span class="progress__label__text vertical-fix">накручено</span>
      </div>
    </div>
    <TaskItem />
  </div>
</template>

<script>
import TaskItem from "./Item"
import { TASK_TYPE_META } from "./"
import { mapState } from "vuex"
import { VNumber } from "@maxflex/v-number"

export default {
  components: { TaskItem, VNumber },

  data() {
    return {
      TASK_TYPE_META,
    }
  },

  computed: {
    ...mapState("task", ["currentTask"]),
  },
}
</script>

<style lang="scss">
.progress {
  &__label {
    font-size: 22px;
    display: flex;
    align-items: center;
    justify-content: center;
    height: 100px;

    & i {
      font-size: 28px;
    }

    &__counter {
      display: inherit;
    }
  }
}

.arrow-down-animated {
  position: relative;
  font-size: 24px;
  margin: 10px 0 5px;
  animation: arrowDown 1s infinite;
}
</style>
