<template lang="pug">
  form(@submit.prevent="$emit('save')")
    input(type="hidden" v-model="messageDto.id")
    div.form-group(:class="this.getErrorName ? 'has-error': 'has-success'")
      label.control-label Имя
      input.form-control(type="text" required v-model="messageDto.name" :disabled="loading")
      p.help-block(v-if="this.getErrorName") {{ this.getErrorName }}

    div.form-group(:class="this.getErrorEmail ? 'has-error' : 'has-success'")
      label.control-label Email
      input.form-control(type="email" required v-model="messageDto.email" :disabled="loading")
      p.help-block(v-if="this.getErrorEmail") {{ this.getErrorEmail }}

    div.form-group(:class="this.getErrorMessage ? 'has-error' : 'has-success'")
      label.control-label Сообщение
      textarea.form-control(name="message" required rows="5" v-model="messageDto.message" :disabled="loading")
      p.help-block(v-if="this.getErrorMessage") {{ this.getErrorMessage }}

    button.btn.btn-primary(v-if="!loading" :disabled="this.errors.hasErrors()" type="submit") {{ this.buttonLabel }}

    ProgressBar(v-if="loading" :progress="100" :max="100")

</template>

<script>
import ProgressBar from "@/Components/ProgressBar";
import EmailValidator from "email-validator";
import ErrorDto from "@/Dto/ErrorDto";
import Errors from "@/Dto/Errors";
import MessageDto from "@/Dto/MessageDto";

export default {

  props: {
    messageDto: {
      type: Object,
      default: new MessageDto(),
    },
    loading: {
      type: Boolean,
      default: false,
    },
    buttonLabel: {
      type: String,
      default: "Добавить отзыв",
    }
  },

  components: {ProgressBar},

  data: () => ({
    errors: new Errors(),
  }),

  methods: {
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
      if (this.messageDto.email.trim() === "" || !EmailValidator.validate(this.messageDto.email)) {
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
