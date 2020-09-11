<template lang="pug">
  div.alert(:class="classInfo")
    button.close(v-if="showClose" type="button" @click="$emit('close')")
      span &times;
    slot
</template>

<script>
export default {
  props: {
    classInfo: {
      type: String,
      default: "alert-info",
    },
    showClose: {
      type: Boolean,
      default: true,
    }
  }
}
</script>
