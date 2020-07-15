<template>
  <div class="stats__item">
    <div class="stats__link mb-2">
      <i class="material-icons text_red mr-2">
        {{ TASK_TYPE_META[item.type].icon }}
      </i>
      <div class="flex-items">
        <span class="text_medium">{{ item.actions_from_count }}</span>
        <span class="mx-2">
          {{ TASK_TYPE_META[item.type].label }}
        </span>
      </div>
      <span class="ml-2">
        <v-chip color="red" v-if="item.is_banned" @click="showBanInfo()">
          бан
        </v-chip>
        <v-chip @click="checkQueue()" color="grey" v-else-if="isInQueue">
          в очереди
        </v-chip>
        <span class="text_grey-light small" v-else-if="isInProgress">
          {{ item.actions_to_count }} накручено
        </span>
        <v-chip v-else color="green" @click="openTaskUrl(item)">
          выполнено
        </v-chip>
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
    <div class="stats__info">
      <div class="text_grey-light xs stats__info__time-ago">
        <template v-if="item.latest_action_created_at !== null">
          {{ item.latest_action_created_at | timeAgo }}
        </template>
      </div>
      <span
        v-if="newNotificationsCount"
        class="stats__info__new-notifications ml-1 text_green heartbeat-animation"
      >
        +{{ newNotificationsCount }} новых
      </span>
    </div>
  </div>
</template>

<script>
import {
  TASK_TYPE_META,
  BAN_REASON_MESSAGE,
  openTaskUrl,
} from "@/components/Task"
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
        const queue = parseInt(r.data) > 1 ? `${r.data}й` : "следующий"
        this.$showMessage(`Вы <span class="text_red">${queue}</span> в очереди`)
      })
    },

    showBanInfo() {
      this.$showMessage(BAN_REASON_MESSAGE[this.item.ban_reason])
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

    newNotificationsCount() {
      return this.$store.state.auth.user.new_notifications[this.item.id]
    },
  },
}
</script>

<style lang="scss">
@import "@/scss/_variables";

.stats {
  &__item {
    &:not(:last-of-type) {
      margin-bottom: 40px;
    }
  }

  &__link {
    display: flex;
    align-items: center;
  }

  &__info {
    margin: 6px 6px 0;
    display: flex;
    justify-content: space-between;
    align-items: center;
    &__time-ago {
      opacity: 0.5;
    }
    &__new-notifications {
      display: inline-block;
      font-size: 13px;
      top: -1px;
      position: relative;
    }
  }

  &__progress {
    height: 36px;
    width: 100%;
    border-radius: 8px;
    background: #f7f7f7;
    overflow: hidden;
    position: relative;

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
          opacity: 0.4;
        }
      }
    }
  }
}
</style>
