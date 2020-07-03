<template>
  <div style="height: 100%">
    <div class="app">
      <transition name="fade">
        <Header v-if="!$route.meta.hasOwnProperty('noHeader')" />
      </transition>

      <vue-page-transition class="app__router-view">
        <router-view></router-view>
      </vue-page-transition>
    </div>

    <v-dialog v-model="$store.state.message.isActive">
      <div v-html="$store.state.message.text"></div>
    </v-dialog>
  </div>
</template>

<script>
import Header from "@/components/Element/Header";

export default {
  components: { Header },

  created() {
    this.$store.dispatch("auth/login");
  },
};
</script>

<style lang="scss">
@import "@/scss/_variables";

.app {
  max-width: $app-max-width;
  min-height: 100%;
  margin: 0 auto;
  position: relative;
  display: flex;
  flex-direction: column;

  &__router-view {
    padding: $app-padding;
    flex: 1;
    display: flex;
    & > div {
      width: 100%;
    }
  }
}
</style>
