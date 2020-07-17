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
    <div class="report" v-if="previousAction !== null">
      <div v-if="reported" class="flex-items justify-center text_green">
        <i class="material-icons mr-2" style="font-size: 20px">
          flag
        </i>
        спасибо – мы её удалим
      </div>
      <a
        v-else
        class="pointer text_grey flex-items justify-center"
        @click="report()"
      >
        <i class="material-icons mr-2" style="font-size: 20px">
          outlined_flag
        </i>
        страница была недоступна
      </a>
    </div>

    <v-dialog @confirm="closeInstructionsDialog()" v-model="instructionsDialog">
      <b
        v-html="TASK_TYPE_META[currentTask.type].instructions.join('<br>')"
      ></b>
      <hr />
      <span class="text_grey text_size-14">
        Будь честным, иначе накрутка заблокируется
      </span>
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
      action: null,
      // Только что выполненный action
      previousAction: null,
      reported: null,
      noMoreTasks: false,
      // DEPRICATED: какие инструкции уже были показаны
      // shownInstructions: {},
      // Инструкция была показана
      instructionsSeen: false,
      instructionsDialog: false,
      // лайк поставлен; при следующем возврате во вкладку
      // будет анимация +1 like
      likeAnimationQueued: false,
    }
  },

  created() {
    this.loadNext()

    // ознакомительные сообщения, если происходит накрутка в первый раз.
    // для каждого типа своё сообщение
    // this.shownInstructions =
    //   JSON.parse(localStorage.getItem("shownInstructions")) || {}

    window.addEventListener("focus", this.onWindowFocus)
  },

  destroyed() {
    window.removeEventListener("focus", this.onWindowFocus)
  },

  methods: {
    onWindowFocus() {
      if (this.likeAnimationQueued) {
        this.currentTask.actions_from_count++
        this.likeAnimationQueued = false
      }
    },

    like() {
      if (this.noMoreTasks) {
        this.noMoreTasksMessage()
        return
      }

      // если накрутка на этот тип задачи впервые – показываем инструкцию
      // if (!(this.currentTask.type in this.shownInstructions)) {

      // если первый лайк на задачу – показываем инструкцию
      if (this.currentTask.actions_from_count === 0 && !this.instructionsSeen) {
        this.instructionsDialog = true
        // this.shownInstructions[this.currentTask.type] = 1
        // localStorage.setItem(
        //   "shownInstructions",
        //   JSON.stringify(this.shownInstructions),
        // )
        return
      }

      this.reported = false

      openTaskUrl(this.action.task)

      this.loadNext()
    },

    loadNext() {
      this.loading = true
      this.noMoreTasks = false
      if (this.action !== null) {
        this.previousAction = this.action
        this.likeAnimationQueued = true
        // this.currentTask.actions_from_count++
      }
      this.$http
        .post([API_URL, "next"].join("/"), {
          task_id_from: this.currentTask.id,
          action_id:
            this.previousAction === null ? undefined : this.previousAction.id,
        })
        .then(r => {
          this.reported = false
          this.action = r.data
        })
        .catch(e => {
          switch (e.response.status) {
            case 404: {
              this.noMoreTasks = true
              this.noMoreTasksMessage()
              break
            }
            case 429: {
              this.$showMessage([
                "Накрутка не засчитана",
                "Обратитесь в поддержку",
              ])
              this.$store.dispatch("task/stop", false)
            }
          }
        })
        .then(() => (this.loading = false))
    },

    noMoreTasksMessage() {
      this.$showMessage([
        "Страницы для обмена закончились",
        "Вернитесь чуть позже",
      ])
    },

    closeInstructionsDialog() {
      this.instructionsSeen = true
      this.instructionsDialog = false
      this.like()
    },

    report() {
      this.reported = true
      this.$http.post("reports", {
        task_id: this.previousAction.task.id,
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
  font-size: 14px;
  margin-top: 38px;
}
</style>
