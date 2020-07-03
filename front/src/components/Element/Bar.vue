<template>
  <div class="bar">
    <div class="bar__controls">
      <div class="relative bar__controls__input">
        <input
          type="text"
          v-model="url"
          ref="url"
          placeholder="https://vk.com/..."
        />
        <div
          class="bar__controls__input__close-button opacity-hover opacity-animation"
          :class="{ 'no-opacity': url.length === 0 }"
          @click="clear"
        >
          <i class="material-icons">close</i>
        </div>
      </div>
      <button @click="click()">
        <span :class="{ invisible: loading }">накрутить</span>
        <Spinner v-show="loading" />
      </button>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    disabled: {
      type: Boolean,
      default: false,
    },

    loading: {
      type: Boolean,
      default: false,
      required: false,
    },

    value: {
      type: String,
      default: "",
    },
  },

  data() {
    return {
      url: "https://vk.com/photo343244650_394923801",
      // url: "",
    }
  },

  created() {
    if (this.value !== "") {
      this.url = this.value
    }
  },

  watch: {
    url(newVal) {
      this.$emit("update:url", newVal)
    },
  },

  methods: {
    click() {
      if (this.url === "") {
        this.$refs.url.focus()
      } else {
        this.$emit("click")
      }
    },

    clear() {
      this.url = ""
      this.$emit("clear")
    },
  },
}
</script>

<style lang="scss">
@import "@/scss/_variables";

.bar {
  width: 100%;
  &__controls {
    display: flex;
    &__input {
      width: 100%;
      & input {
        width: 100%;
        outline: none;
        border: 3px solid $blue;
        box-sizing: border-box;
        flex: 1;
        padding: 8px 35px 8px 8px;
        border-right: 0;
        border-radius: 4px 0 0 4px;
      }
      &__close-button {
        position: absolute;
        top: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 100%;
        right: 3px;
        & i {
          font-size: 30px;
        }
        // font-size: 28px;
      }
    }
    & button {
      background-color: $blue;
      color: white;
      border: none;
      text-transform: uppercase;
      border-left: 0;
      border-radius: 0 4px 4px 0;
      position: relative;
      cursor: pointer;
      & svg {
        position: absolute;
        top: 0;
        width: 100%;
        left: 0;
        padding: 5px;
        box-sizing: border-box;
      }
    }
  }
}
</style>
