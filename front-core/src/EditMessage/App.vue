<template lang="pug">
  div
    div(v-if="success")
      h3 Редактирование сообщения
      FeedbackForm(v-if="!successUpdate" @save="update" :messageDto="messageDto" :loading="loading" buttonLabel="Обновить")
      p &nbsp;
      Alert.alert-success(v-if="successUpdate") Сообщение обновлено!
      Alert.alert-danger(v-if="!successUpdate && errorMessage" @close="errorMessage = ''")
        | #[i.fa.fa-exclamation-triangle]
        | {{ errorMessage }}
    Alert.alert-danger(v-if="!success") {{ errorMessage }}
</template>

<script>
import Api from "@/Service/Api";
import Alert from "@/Components/Alert";
import FeedbackForm from "@/Components/FeedbackForm";
import MessageDto from "@/Dto/MessageDto";

export default {
  components: {Alert, FeedbackForm},
  data: () => ({
    messageDto: new MessageDto(),
    success: false,
    loading: false,
    errorMessage: "",
    successUpdate: false,
  }),
  methods: {
    async update() {
      this.loading = true;
      const responseApiDto = await Api.store(this.messageDto);
      this.successUpdate = responseApiDto.success;
      this.errorMessage = responseApiDto.message;
      this.loading = false;
    }
  },
  async mounted() {
    this.loading = true;
    const regResult = /id=(\d+)/.exec(location.search);
    const messageId = regResult ? parseInt(regResult[1]) : 0;
    const messageApiDto = await Api.getMessage(messageId);
    this.success = messageApiDto.success;
    this.errorMessage = messageApiDto.message;
    if (this.success) {
      this.messageDto = messageApiDto.messageDto;
    }
    this.loading = false;
  }
}
</script>
