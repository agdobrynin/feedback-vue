<template>
  <div>
    <alert v-if="!success" @close="success = true" :class-info="success ? 'alert-success' : 'alert-danger'">
      {{ errorMessage }}
    </alert>
    <alert v-if="success && !loading && messageCollection.length === 0">
      Записей не найдено!
    </alert>
    <nav v-if="this.messageCollection.length">
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
        <div class="panel-footer text-muted">
          сообщение добавлено в {{ message.createdAt }} (id={{ message.id }})
          <a :href="`/feedback/edit-form?id=${message.id}`" class="btn btn-xs btn-success">редактировать</a>
          &nbsp;
          <a class="btn btn-xs btn-danger" @click.stop="deleteMessage(message.id)">удалить</a>
        </div>
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
    async loadMessageOnPage(page) {
      const messageCollectionApiDto = await Api.getMessagesOnPage(page);
      this.success = messageCollectionApiDto.success;
      this.errorMessage = messageCollectionApiDto.message;
      if (this.success) {
        this.messageCollection = messageCollectionApiDto.messageCollection;
      }
    },
    async loadPages() {
      const pagesApiDto = await Api.getPages();
      this.success = pagesApiDto.success;
      this.errorMessage = pagesApiDto.message;
      if (pagesApiDto.success) {
        this.pages = Array.from(Array(pagesApiDto.pages), (_, i) => i + 1);
      }
    },
    async getPage(page) {
      if (this.currentPage === page) {
        return;
      }
      this.loading = true;
      await this.loadMessageOnPage(page);
      if (this.success && this.messageCollection.length) {
        this.currentPage = page;
        location.hash = `#page=${page}`;
      }
      this.loading = false;
    },
    async deleteMessage(id) {
      this.loading = true;
      const responseApiDto = await Api.deleteMessage(id);
      this.success = responseApiDto.success;
      this.errorMessage = responseApiDto.message;
      if (this.success) {
        await this.loadPages();
        await this.loadMessageOnPage(this.currentPage);
        if (this.success && this.messageCollection.length === 0) {
          this.currentPage = 1;
          await this.loadMessageOnPage(this.currentPage);
        }
      }
      this.loading = false;
    },
  },
  async mounted() {
    this.loading = true;
    await this.loadPages();
    if (this.success) {
      let defaultStartPage = 1;
      const regResult = /page=(\d+)/.exec(location.hash);
      const pageNumberFromParam = regResult ? parseInt(regResult[1]) : defaultStartPage;
      if (pageNumberFromParam > 0 && pageNumberFromParam <= this.pages.length ) {
        defaultStartPage = pageNumberFromParam;
      }
      this.currentPage = defaultStartPage;
      this.loadMessageOnPage(defaultStartPage);
    }
    this.loading = false;
  }
}
</script>
