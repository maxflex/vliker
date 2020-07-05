<template>
  <div>
    <transition name="fade">
      <v-overlay @click="value = false" v-if="value"></v-overlay>
    </transition>

    <transition
      enter-active-class="animated fadeIn"
      leave-active-class="animated fadeOut"
    >
      <div class="v-dialog" v-if="value">
        <div class="v-dialog__content">
          <slot></slot>
          <div class="v-dialog__content__actions">
            <v-button @click.native="handleConfirm">
              {{ label }}
            </v-button>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
import VOverlay from "@/components/UI/VOverlay"

export default {
  name: "v-dialog",

  components: { VOverlay },

  props: {
    label: {
      type: String,
      default: "OK",
    },

    value: {
      type: Boolean,
    },
  },

  methods: {
    handleConfirm() {
      this.$emit("confirm")
      this.$emit("input", false)
    },
  },
}
</script>

<style lang="scss">
.v-dialog {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  display: flex;
  align-items: center;
  justify-content: center;
  z-index: 10;

  &__content {
    min-width: 300px;
    max-width: 800px;
    background: white;
    padding: 10px;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.33);
    border-radius: 2px;
    text-align: center;

    &__actions {
      margin-top: 30px;
      text-align: center;
    }
  }
}
</style>
