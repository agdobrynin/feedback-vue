<template>
  <div>
    <alert v-if="!success" @close="success = true" :class-info="success ? 'alert-success' : 'alert-danger'">
      {{ errorMessage }}
    </alert>
    <alert v-if="success && !loading && messageCollection.length === 0">
      Записей не найдено!
    </alert>
    <nav>
      <ul class="pagination">
        <li v-for="page in pages" :key="page" :class="{active : currentPage === page}">
          <a href="#" class="page" @click.prevent="getPage(page)">{{ page }}</a>
        </li>
      </ul>
    </nav>
    <ProgressBar v-if="loading" :progress="100" :max="100"></ProgressBar>
    <div v-if="!loading">
      <div v-for="message in messageCollection" :key="message.id" class="panel panel-info">
        <div class="panel-heading"><strong>Имя: {{ message.name }}, Email: {{ message.email }}</strong></div>
        <div class="panel-body">{{ message.message }}</div>
        <div class="panel-footer text-muted">сообщение добавлено в {{ message.createdAt }} (id= {{ message.id }})</div>
      </div>
    </div>
  </div>
</template>

<script>
import Api from "@/Service/Api";
import Alert from "@/Components/Alert";
import ProgressBar from "@/Components/ProgressBar";

export default {
  components: {Alert, ProgressBar},
  data: () => ({
    loading: false,
    success: true,
    errorMessage: "",
    messageCollection: [],
    currentPage: 0,
    pages: [],
  }),
  methods: {
    async getPage(page) {
      if (this.currentPage === page) {
        return;
      }
      this.loading = true;
      const messagesConnection = await Api.getMessagesOnPage(page);
      this.success = messagesConnection.success;
      this.errorMessage = messagesConnection.message;
      this.messageCollection = messagesConnection.messageCollection;
      if (this.success) {
        this.currentPage = page;
      }
      this.loading = false;
    }
  },
  async mounted() {
    this.loading = true;
    const pagesApiDto = await Api.getPages();
    this.success = pagesApiDto.success;
    this.errorMessage = pagesApiDto.message;
    this.pages = Array.from(Array(pagesApiDto.pages), (_, i) => i + 1);
    let defaultStartPage = 1;
    const regResult = /page=(\d+)/.exec(location.search);
    const pageNumberFromParam = regResult ? parseInt(regResult[1]) : defaultStartPage;
    if (pageNumberFromParam > 0 && pageNumberFromParam <= this.pages ) {
      defaultStartPage = pageNumberFromParam;
    }
    this.getPage(defaultStartPage);
    this.loading = false;
  }
}
</script>
