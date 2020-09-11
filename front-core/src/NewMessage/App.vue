<template lang="pug">
  div
    h3(v-if="!success") Отправить сообщение
    FeedbackForm(v-if="!success" @save="store" :messageDto="messageDto" :loading="loading")
    p &nbsp;
    Alert(v-if="hasResponse" @close="hasResponse = false" :class-info="success ? 'alert-success' : 'alert-danger'" :show-close="!success")
      strong(v-if="!success") #[i.fa.fa-exclamation-triangle] Ошибка от сервера:&nbsp;
      | {{ responseMessage }}
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
