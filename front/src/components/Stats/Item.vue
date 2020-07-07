<template>
  <div class="stats__item">
    <div class="stats__link mb-2">
      <i class="material-icons text_red mr-2">
        {{ TASK_TYPE_META[item.type].icon }}
      </i>
      <div>
        <span class="text_medium">{{ item.actions_from_count }}</span>
        {{ TASK_TYPE_META[item.type].label }}
      </div>
      <span class="ml-3">
        <v-chip color="red" v-if="item.is_banned" @click="showBanInfo()">
          бан
        </v-chip>
        <v-chip @click="checkQueue()" color="grey" v-else-if="isInQueue">
          в очереди
        </v-chip>
        <span class="text_grey-light" v-else-if="isInProgress">
          {{ item.actions_to_count }} накручено
        </span>
        <v-chip v-else color="green">выполнено</v-chip>
      </span>
      <spacer></spacer>
      <v-menu>
        <div v-if="isInQueue" @click="checkQueue()">
          Какой я в очереди?
        </div>
        <div @click="startAgain()">
          Продолжить накрутку
        </div>
        <div @click="openTaskUrl(item)">
          Перейти на страницу
        </div>
      </v-menu>
    </div>
    <div class="stats__progress">
      <div
        class="stats__progress__completed"
        :class="{
          'stats__progress__completed_in-progress': isInProgress,
        }"
        :style="{ width: percentage + '%' }"
      ></div>
    </div>
  </div>
</template>

<script>
import { TASK_TYPE_META, openTaskUrl } from "@/components/Task"
import VMenu from "@/components/UI/VMenu"

export default {
  props: {
    item: {
      type: Object,
    },
  },

  components: { VMenu },

  data() {
    return {
      TASK_TYPE_META,
    }
  },

  methods: {
    openTaskUrl,

    startAgain() {
      if (this.item.is_banned) {
        this.showBanInfo()
      }
      this.$store.dispatch("task/start", this.item)
    },

    checkQueue() {
      this.$store.dispatch("task/checkQueue", this.item).then(r => {
        this.$showMessage(
          `Вы <span class="text_red">${r.data}й</span> в очереди`,
        )
      })
    },

    showBanInfo() {
      this.$showMessage([
        "Страница заблокирована из-за жалоб",
        "Она точно доступна и открыта для всех?",
      ])
    },
  },

  computed: {
    percentage() {
      return (this.item.actions_to_count / this.item.actions_from_count) * 100
    },

    isInProgress() {
      return (
        !this.item.is_banned && this.percentage > 0 && this.percentage < 100
      )
    },

    // задача в очереди
    isInQueue() {
      return !this.item.is_banned && this.item.actions_to_count === 0
    },
  },
}
</script>

<style lang="scss">
@import "@/scss/_variables";

.stats {
  &__link {
    display: flex;
    align-items: center;
  }

  &__progress {
    height: 34px;
    width: 100%;
    border-radius: 8px;
    background: #f7f7f7;
    overflow: hidden;

    &__completed {
      height: 100%;
      $color-start: #d0dfea;
      $color-end: $blue;
      background: $color-end; /* fallback for old browsers */
      background: -webkit-linear-gradient(
        to right,
        $color-start,
        $color-end
      ); /* Chrome 10-25, Safari 5.1-6 */
      background: linear-gradient(
        to right,
        $color-start,
        $color-end
      ); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
      position: relative;
      box-shadow: 0 0 16px 3px $color-end;
      &_in-progress {
        &:before {
          content: "";
          background-image: url("/img/patterns/stripe.png");
          animation: patterns 60s linear infinite;
          position: absolute;
          height: 100%;
          width: 100%;
          background-repeat: repeat;
          background-size: 175px;
          opacity: 0.25;
        }
      }
    }
  }
}
</style>
