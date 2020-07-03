<template>
  <div class="header">
    <div>
      <transition name="fade">
        <i
          v-if="$route.meta.backButton"
          class="material-icons header__back-button pointer"
          @click="$router.go(-1)"
        >
          keyboard_arrow_left
        </i>
      </transition>
    </div>
    <h1 class="header__title">
      <transition name="fade">
        <span v-if="$route.meta.title">
          {{ $route.meta.title }}
        </span>
      </transition>
    </h1>
    <div class="header__controls">
      <router-link :to="{ name: 'StatsPage' }" v-if="user && user.has_tasks">
        <i
          :class="{
            'has-new': user.notification_count > 0,
          }"
          class="material-icons pointer"
          >notifications</i
        >
      </router-link>
    </div>
  </div>
</template>

<script>
import { mapState } from "vuex";

export default {
  computed: {
    ...mapState("auth", ["user"]),
  },
};
</script>

<style lang="scss">
.header {
  position: sticky;
  top: 0;
  padding-bottom: 20px;
  box-sizing: border-box;
  display: flex;
  justify-content: space-between;
  width: 100%;
  align-items: center;
  background: white;
  box-shadow: 0 0 8px 3px white;
  z-index: 1;
  padding: 10px;

  & i {
    font-size: 30px;
  }

  &__back-button {
    margin-left: -4px;
  }

  &__controls {
  }

  &__title {
    text-transform: uppercase;
    font-weight: 500;
    flex: 1;
    text-align: center;
  }
}
</style>
