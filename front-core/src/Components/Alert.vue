<template>
  <div class="alert" :class="classInfo">
    <button v-if="showClose" type="button" class="close" @click="$emit('close')"><span aria-hidden="true">&times;</span></button>
    <slot></slot>
  </div>
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
