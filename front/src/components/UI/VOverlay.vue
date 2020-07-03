<template>
  <div @click="$emit('click')" class='v-overlay'></div>
</template>

<script>
export default {
  name: 'v-overlay'
}
</script>

<style lang='scss'>
  .v-overlay {
    position: fixed;
    top: 0;
    left: 0;
    height: 100%;
    width: 100%;
    z-index: 9;
    &:before {
      content: '';
      position: absolute;
      height: 100%;
      width: 100%;
      background: black;
      opacity: .5;
    }
  }
</style>