<template>
  <div class="task-types">
    <div
      class="task-types__item"
      v-for="option in taskType.option"
      @click="$emit('input', option)"
      :class="{ 'task-types__item_active': value === option }"
      :key="option"
    >
      <div class="task-types__title">{{ taskType.title[option] }}</div>
      <div class="task-types__img">
        <component :is="'Icon' + capitalize(option)" />
      </div>
    </div>
  </div>
</template>

<script>
import { capitalize } from "lodash"
import taskType from "@/enums/task-type"

export default {
  props: {
    value: {
      type: String,
      required: true,
      validator(value) {
        return taskType.values.includes(value)
      },
    },
  },

  data() {
    return {
      taskType,
      capitalize,
    }
  },
}
</script>

<style lang="scss">
@import "@/scss/_variables";

.task-types {
  margin-top: 60px;
  display: flex;
  justify-content: space-between;
  flex-wrap: wrap;
  padding: 5px;
  &__item {
    text-align: center;
    // border: 3px solid $grey;
    padding: 10px;
    border-radius: $border-radius;
    // margin: 0 20px;
    max-width: 250px;
    width: 25%;
    cursor: pointer;
    transition: all 0.2s linear;
    // &:first-child {
    //   margin-left: 0;
    // }
    // &:last-child {
    //   margin-right: 0;
    // }
    &:hover:not(.task-types__item_active) {
      background: $blue-very-light;
    }
    &_active {
      transform: scale(1.15);
      // border-color: transparent;
      // padding: 13px;
      box-shadow: 0 0 15px #ff416c;
      background: #ff416c; /* fallback for old browsers */
      background: -webkit-linear-gradient(
        to bottom,
        #ff4b2b,
        #ff416c
      ); /* Chrome 10-25, Safari 5.1-6 */
      background: linear-gradient(
        to bottom,
        #ff4b2b,
        #ff416c
      ); /* W3C, IE 10+/ Edge, Firefox 16+, Chrome 26+, Opera 12+, Safari 7+ */
      & .task-types__title {
        color: white;
      }
      & .task-types__img svg {
        opacity: 1 !important;
        fill: white !important;
      }
    }
  }
  &__title {
    margin-bottom: $spacing-1;
    color: $grey;
  }
  &__img {
    & svg {
      width: 100%;
      height: 60px;
      opacity: 0.5;
    }
  }
}
</style>
