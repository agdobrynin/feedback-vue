<template>
  <div>
    <form action="/store" @submit.prevent="store" v-if="!success">
      <h3>Отправить сообщение</h3>
      <div class="form-group">
        <label>Имя</label>
        <input type="text" class="form-control" required v-model="message.name">
      </div>
      <div class="form-group">
        <label>Email</label>
        <input type="email" class="form-control" required v-model="message.email">
      </div>
      <div class="form-group">
        <label>Сообщение</label>
        <textarea name="message" required class="form-control" rows="5" v-model="message.message"></textarea>
      </div>
      <div class="progress" v-if="loading">
        <div class="progress-bar progress-bar-striped active" style="width: 100%;"></div>
      </div>
      <button type="submit" class="btn btn-default" v-if="!loading">Добавить отзыв</button>
    </form>
    <p>&nbsp;</p>
    <alert v-if="hasResponse" :class-info="success ? 'alert-success' : 'alert-danger'">
      {{ responseMessage }}
    </alert>
  </div>
</template>

<script>
import MessageDto from "@/Dto/MessageDto";
import Api from "@/Service/Api";
import Alert from "@/Components/Alert";

export default {
  name: 'app',
  components: {Alert},
  data: () => ({
    message: new MessageDto(),
    hasResponse: false,
    success: false,
    loading: false,
    responseMessage: "",
  }),
  methods: {
    async store() {
      this.loading = true;
      const responseApiDto = await Api.store(this.message);
      this.hasResponse = responseApiDto;
      this.success = responseApiDto.success;
      this.responseMessage = responseApiDto.message;
      this.loading = false;
    }
  }
}
</script>
