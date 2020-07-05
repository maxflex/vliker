<template>
  <div>
    <div class="task-item" @click="like()">
      <Spinner color="#5275A0" v-if="loading" />
      <div class="task-item__item" v-else>
        <i class="material-icons" v-if="noMoreTasks">mood_bad</i>
        <component
          v-else
          :is="'Icon' + capitalize(currentTask.type)"
          class="heartbeat-animation"
        />
      </div>
    </div>
    <div class="report" v-if="targetTaskId !== null">
      <span v-if="reported" class="text_green"
        >Спасибо! Cтраница будет удалена</span
      >
      <a v-else class="grey-link" @click="report">
        Страница была закрыта/недоступна
      </a>
    </div>

    <v-dialog
      @onConfirm="
        firstTimeInstructionDialog = false
        like()
      "
      v-model="firstTimeInstructionDialog"
    >
      <span
        v-html="TASK_TYPE_META[currentTask.type].instruction.join('<br>')"
      ></span>
    </v-dialog>
  </div>
</template>

<script>
import { API_URL, TASK_TYPE_META, openTaskUrl } from "@/components/Task"
import { mapState } from "vuex"
import { capitalize } from "lodash"

export default {
  data() {
    return {
      capitalize,
      TASK_TYPE_META,
      loading: true,
      item: null,
      targetTaskId: null, // ID только что выполненного задания
      reported: null,
      noMoreTasks: false,
      instructions: {},
      firstTimeInstructionDialog: false,

      // лайк поставлен; при следующем возврате во вкладку
      // будет анимация +1 like
      likeAnimationQueued: false,
    }
  },

  created() {
    this.loadNext()

    // ознакомительные сообщения, если происходит накрутка в первый раз.
    // для каждого типа своё сообщение
    this.instructions = JSON.parse(localStorage.getItem("instructions")) || {}

    window.addEventListener("focus", this.onWindowFocus)
  },

  destroyed() {
    window.removeEventListener("focus", this.onWindowFocus)
  },

  methods: {
    onWindowFocus() {
      if (this.likeAnimationQueued) {
        this.currentTask.completed++
        this.likeAnimationQueued = false
      }
    },

    like() {
      if (this.noMoreTasks) {
        this.noMoreTasksMessage()
        return
      }

      // если накрутка впервые – показываем сообщение-инструкцию
      if (!(this.currentTask.type in this.instructions)) {
        this.firstTimeInstructionDialog = true
        this.instructions[this.currentTask.type] = 1
        localStorage.setItem("instructions", JSON.stringify(this.instructions))
        return
      }

      this.reported = false

      openTaskUrl(this.item)

      this.loadNext()
    },

    loadNext() {
      this.loading = true
      this.noMoreTasks = false
      if (this.item !== null) {
        this.targetTaskId = this.item.id
        this.likeAnimationQueued = true
        // this.currentTask.completed++
      }
      this.$http
        .post([API_URL, "next"].join("/"), {
          target_task_id: this.targetTaskId,
          completed_by_task_id: this.currentTask.id,
        })
        .then(r => {
          this.reported = false
          this.item = r.data
        })
        .catch(e => {
          if (e.response.status === 404) {
            this.noMoreTasks = true
            this.noMoreTasksMessage()
          }
        })
        .then(() => (this.loading = false))
    },

    noMoreTasksMessage() {
      this.$store.dispatch("message/set", [
        "Страницы для обмена закончились",
        "Вернитесь чуть позже",
      ])
    },

    report() {
      this.reported = true
      this.$http.post("reports", {
        reported_task_id: this.targetTaskId,
      })
    },
  },

  computed: {
    ...mapState("task", ["currentTask"]),
  },
}
</script>

<style lang="scss">
@import "@/scss/_variables";

.task-item {
  width: 50%;
  width: 240px;
  height: 240px;
  text-align: center;
  border: 3px solid $blue;
  margin: 10px auto;
  border-radius: 18px;
  display: flex;
  padding: 30px;
  box-sizing: border-box;
  opacity: 0.4;
  cursor: pointer;
  transition: opacity ease-in 0.1s;
  align-items: center;
  justify-content: center;
  &__item {
    pointer-events: none;
    & .material-icons {
      font-size: 100px;
    }
  }
  &:hover {
    opacity: 1;
  }
  & svg {
    width: 100%;
  }
}

.report {
  text-align: center;
  font-size: 10px;
}
</style>
