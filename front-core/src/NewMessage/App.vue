<template lang="pug">
  div
    h3(v-if="!success") Отправить сообщение
    FeedbackForm(v-if="!success" @save="store" :messageDto="messageDto" :loading="loading")
    p &nbsp;
    Alert(v-if="hasResponse && success" class-info="alert-success" icon="fa-check") {{ responseMessage }}
    Alert(v-if="hasResponse && !success" class-info="alert-danger" @close="hasResponse=false" icon="fa-exclamation-triangle") {{ responseMessage }}
</template>

<script>
import MessageDto from "@/Dto/MessageDto";
import Api from "@/Service/Api";
import Alert from "@/Components/Alert";
import FeedbackForm from "@/Components/FeedbackForm";

export default {
  components: {Alert, FeedbackForm},

  data: () => ({
    messageDto: new MessageDto(),
    hasResponse: false,
    success: false,
    loading: false,
    responseMessage: "",
  }),

  methods: {

    async store() {
      this.loading = true;
      const responseApiDto = await Api.store(this.messageDto);
      this.hasResponse = responseApiDto;
      this.success = responseApiDto.success;
      this.responseMessage = responseApiDto.message;
      this.loading = false;
    },

  },
}
</script>
