<template lang="pug">
  div
    Alert.alert-danger(v-if="!successLoad" icon="fa-exclamation-triangle") {{ errorMessage }}
    div(v-if="successLoad")
        h3 Редактирование сообщения
        FeedbackForm(v-if="!successUpdate" @save="update" :messageDto="messageDto" :loading="loading" buttonLabel="Обновить")
        p &nbsp;
        Alert(v-if="successUpdate" icon="fa-check" class-info="alert-success") Сообщение обновлено!
        Alert(v-if="!successUpdate && errorMessage" @close="errorMessage = ''" icon="fa-exclamation-triangle" class-info="alert-danger")
          | {{ errorMessage }}
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
    successLoad: false,
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
    this.successLoad = messageApiDto.success;
    this.errorMessage = messageApiDto.message;
    if (this.successLoad) {
      this.messageDto = messageApiDto.messageDto;
    }
    this.loading = false;
  }
}
</script>
