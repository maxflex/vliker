<template>
  <div class='text-placeholder_with-icon mt-3'>
    <span v-if="Object.keys(item).length > 0" class='text_red text_size-14 flex-items' >
      <i class='material-icons mr-2'>error_outline</i>
      {{ errorText }}
    </span>
  </div>
</template>

<script>
export default {
  props: {
    item: {
      type: Object,
      required: true,
    }
  },

  computed: {
    errorText() {
      return Object.values(this.item.data.errors)[0][0]
    }
  }
}
</script>
