<template>
  <div>
    <div v-if="success">
      <h3>Редактирование сообщения</h3>
      <FeedbackForm v-if="!successUpdate" @save="update" :messageDto="messageDto" :loading="loading" buttonLabel="Обновить"></FeedbackForm>
      <p>&nbsp;</p>
      <Alert v-if="successUpdate" class="alert-success">
        Сообщение обновлено!
      </Alert>
      <Alert v-if="!successUpdate && errorMessage" class="alert-danger" @close="errorMessage = ''">
        {{ errorMessage }}
      </Alert>
    </div>
    <Alert v-if="!success" class="alert-danger">
      {{ errorMessage }}
    </Alert>
  </div>
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
