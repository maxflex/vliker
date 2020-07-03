<template>
  <div class='context-menu'>
    <i class='material-icons' @click='isActive = !isActive'>
      more_horiz
    </i>
    <transition name="fade">
      <div
        v-if='isActive' 
        class='context-menu__items'
        v-click-outside='handleClickOutside'
      >
        <span>test</span>
      </div>
    </transition>
  </div>
</template>

<script>
export default {
  name: 'v-context-menu',

  data() {
    return {
      isActive: false,
    }
  },

  methods: {
    // TODO: покамест не работает
    handleClickOutside() {
      this.$nextTick(() => {
        if (this.isActive) {
          this.isActive = false
        }
      })
    }
  }
}
</script>

<style lang='scss'>
.context-menu {
  &__items {
    position: absolute;
    z-index: 2;
    background: white;
    padding: 4px 6px;
    box-shadow: 0 0 6px;
    border-radius: 2px;
  }
}
</style>