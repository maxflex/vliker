<template>
  <button class="v-button" :class="{ 'v-button_with-icon': $slots['icon'] }">
    <slot name="icon"></slot>
    <slot></slot>
  </button>
</template>

<script>
export default {
  props: {
    icon: {
      type: String,
      default: null,
    },
  },
}
</script>

<style lang="scss">
@import "@/scss/_variables";

.v-button {
  box-sizing: border-box;
  padding: 10px 25px;
  border-radius: 4px;
  border: 0;
  background: $blue;
  color: white;
  outline: none;
  cursor: pointer;
  position: relative;
  &:hover {
    background: lighten($blue, 5);
  }
  &_with-icon {
    & .ion {
      position: absolute;
      & svg {
        height: 24px;
        width: 24px;
      }
      top: 7px;
      left: 16px;
    }
    & span {
      padding-left: 18px;
    }
  }
}
</style>
