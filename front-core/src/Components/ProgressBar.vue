<template>
  <div class="progress">
    <div class="progress-bar progress-bar-striped active" :style="style"></div>
  </div>
</template>

<script>
export default {
  name: "ProgressBar",
  props: {
    progress: {
      type: Number,
      default: 0.0,
    },
    max: {
      type: Number,
      default: 100.0,
    }
  },
  computed: {
    style() {
      return `width: ${(this.progress / this.max)*100}%`;
    }
  }
}
</script>
