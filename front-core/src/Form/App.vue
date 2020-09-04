<template>
  <div>
    <form action="/store" @submit.prevent="store" v-if="!success">
      <h3>Отправить сообщение</h3>

      <div class="form-group" :class="this.getErrorName ? 'has-error': 'has-success'">
        <label class="control-label">Имя</label>
        <input type="text" class="form-control" required v-model="messageDto.name" :disabled="loading">
        <p v-if="this.getErrorName" class="help-block">{{ this.getErrorName }}</p>
      </div>

      <div class="form-group" :class="this.getErrorEmail ? 'has-error' : 'has-success'">
        <label class="control-label">Email</label>
        <input type="email" class="form-control" required v-model="messageDto.email" :disabled="loading">
        <p v-if="this.getErrorEmail" class="help-block">{{ this.getErrorEmail }}</p>
      </div>

      <div class="form-group" :class="this.getErrorMessage ? 'has-error' : 'has-success'">
        <label class="control-label">Сообщение</label>
        <textarea name="message" required class="form-control" rows="5" v-model="messageDto.message" :disabled="loading">
        </textarea>
        <p v-if="this.getErrorMessage" class="help-block">{{ this.getErrorMessage }}</p>
      </div>

      <ProgressBar v-if="loading" :progress="100" :max="100"></ProgressBar>

      <button type="submit" class="btn btn-primary" v-if="!loading" :disabled="this.errors.hasErrors()">
        Добавить отзыв
      </button>
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
import ProgressBar from "@/Components/ProgressBar";
import Errors from "@/Dto/Errors";
import ErrorDto from "@/Dto/ErrorDto";

const regEmail = new RegExp('^(([^<>()\\[\\]\\.,;:\\s@\\"]+(\\.[^<>()\\[\\]\\.,;:\\s@\\"]+)*)|(\\".+\\"))@(([^<>()[\\]\\.,;:\\s@\\"]+\\.)+[^<>()[\\]\\.,;:\\s@\\"]{2,})$', 'i');

export default {
  name: 'app',
  components: {Alert, ProgressBar},
  data: () => ({
    messageDto: new MessageDto(),
    hasResponse: false,
    success: false,
    loading: false,
    responseMessage: "",
    errors: new Errors(),
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

    validateName() {
      const key = "name";
      this.errors.unset(key);
      if (this.messageDto.name.trim() === "") {
        this.errors.add(new ErrorDto(key, "Поле Имя обязатльное."));
      }
    },

    validateEmail() {
      const key = "email";
      this.errors.unset(key);
      if (this.messageDto.email.trim() === "" || !regEmail.test(this.messageDto.email)) {
        this.errors.add(new ErrorDto(key, "Проверьте поле Email."));
      }
    },

    validateMessage() {
      const key = "message";
      this.errors.unset(key);
      if (this.messageDto.message.trim() === "") {
        this.errors.add(new ErrorDto(key, "Заполните поле Сообщение."));
      }
    },

  },

  computed: {

    hasError() {
      return this.errors.errors.length > 0;
    },

    getErrorName() {
      return this.errors.get("name")?.message;
    },

    getErrorEmail() {
      return this.errors.get("email")?.message;
    },

    getErrorMessage() {
      return this.errors.get("message")?.message;
    },

  },

  created() {
    this.validateName();
    this.validateEmail();
    this.validateMessage();
  },

  watch: {
    "messageDto.name": {
      handler: function () {
        this.validateName();
      }
    },
    "messageDto.email": {
      handler: function () {
        this.validateEmail()
      }
    },
    "messageDto.message": {
      handler: function () {
        this.validateMessage();
      }
    },
  },
}
</script>
