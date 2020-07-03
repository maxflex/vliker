<template>
  <div class="v-menu">
    <transition name="fade">
      <v-overlay @click="isActive = false" v-if="isActive"></v-overlay>
    </transition>
    <i class="material-icons pointer" @click="isActive = !isActive">
      more_horiz
    </i>

    <transition
      enter-active-class="animated fadeInUp"
      leave-active-class="animated fadeOutDown"
    >
      <div v-show="isActive" class="v-menu__items">
        <div class="v-menu__items__group" @click="isActive = false">
          <slot></slot>
        </div>
        <div class="v-menu__items__group">
          <div class="text_red" @click="isActive = false">
            Отмена
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
import VOverlay from "@/components/UI/VOverlay"

export default {
  name: "v-menu",

  components: { VOverlay },

  created() {
    this.$on("click", () => console.log("clicked"))
  },

  data() {
    return {
      isActive: false,
    }
  },
}
</script>

<style lang="scss">
@import "@/scss/_variables";

.v-menu {
  &__items {
    position: fixed;
    z-index: 10;
    bottom: 20px;
    left: 0;
    width: 100%;

    &__group {
      display: flex;
      flex-direction: column;
      border-radius: 6px;
      overflow: hidden;
      width: 90%;
      max-width: $app-max-width;
      margin: 0 auto;
      background: white;

      & > div {
        padding: 20px 10px;
        text-align: center;
        cursor: pointer;
        // font-weight: 500;

        &:not(:last-child) {
          border-bottom: 1px solid gainsboro;
        }

        &:hover {
          background: $blue-very-light;
        }
      }

      &:not(:last-child) {
        margin-bottom: 20px;
      }
    }
  }
}
</style>
