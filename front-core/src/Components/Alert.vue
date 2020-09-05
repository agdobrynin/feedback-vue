<template>
  <div class="alert" :class="classInfo">
    <button type="button" class="close" @click="$emit('close')"><span aria-hidden="true">&times;</span></button>
    <slot></slot>
  </div>
</template>

<script>
export default {
  props: {
    classInfo: {
      type: String,
      default: "alert-info",
    }
  }
}
</script>
