<template>
  <div class="v-chip" :class="color" @click="$emit('click')">
    <slot></slot>
  </div>
</template>

<script>
export default {
  props: {
    color: {
      type: String,
      default: "blue",
    },
  },
}
</script>

<style lang="scss">
.v-chip {
  color: white;
  border-radius: 4px;
  font-size: 0.8em;
  padding: 2px 6px 3px;
  font-weight: 500;
  letter-spacing: 1px;
  cursor: pointer;
  position: relative;
}
</style>
