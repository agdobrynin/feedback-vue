<template>
  <div class="alert" :class="classInfo">
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
