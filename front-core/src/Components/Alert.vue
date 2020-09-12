<template lang="pug">
  .alert(:class="classInfo")
    .media
      button.close(v-if="showClose" type="button" @click="close") #[span &times;]
      .media-left(v-if="icon")
        i.fa.fa-icon.fa-2x(:class="icon")
      .media-body
        slot
</template>

<script>
export default {
  props: {
    classInfo: {
      type: String,
      default: "alert-info",
    },
    icon: {
      type: String,
      default: "",
    },
  },
  data: () => ({
    showClose: false,
  }),
  methods: {
    close() {
      this.$emit('close');
    },
  },
  mounted() {
    this.showClose = this.$listeners.close !== undefined;
  }
}
</script>
