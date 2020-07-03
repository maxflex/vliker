<template>
  <div class="page-index">
    <!-- header -->
    <div></div>

    <!-- body -->
    <div>
      <div class="page-index__logo">
        <img src="/img/logo/logo.png" />
      </div>
      <Bar
        :loading="loading"
        :url.sync="task.url"
        @click="start()"
        @clear="error = {}"
      />
      <VError :item="error" />
      <TypeSelector v-model="task.type" />
    </div>

    <!-- footer -->
    <div></div>
  </div>
</template>

<script>
import Bar from "@/components/Element/Bar"
import TypeSelector from "@/components/Task/TypeSelector"
import { TASK_TYPE } from "@/enums/task-type"

export default {
  components: { Bar, TypeSelector },

  props: {
    disabled: {
      type: Boolean,
      default: false,
    },
  },

  // beforeRouteEnter(to, from, next) {
  //   $('#vk_community_messages').show()
  //   next()
  // },

  // beforeRouteLeave(to, from, next) {
  //   $('#vk_community_messages').hide()
  //   next()
  // },

  data() {
    return {
      task: {
        url: null,
        type: TASK_TYPE.like,
      },
      error: {},
      loading: false,
    }
  },

  methods: {
    async start() {
      this.error = {}
      this.loading = true
      if (this.$store.state.auth.user === null) {
        await this.$store.dispatch("auth/createUser")
      }
      await this.$store.dispatch("task/start", this.task).catch(error => {
        this.error = error.response
      })
      this.loading = false
    },
  },
}
</script>

<style lang="scss">
@import "@/scss/_variables";

.page-index {
  align-self: center;

  &__logo {
    text-align: center;
    & img {
      width: 50%;
      max-width: 500px;
      margin-bottom: 5px;
    }
  }
}
</style>
