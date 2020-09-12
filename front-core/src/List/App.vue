<template lang="pug">
    div
        Alert(v-if="requestSuccess && !loading && messageCollection.length === 0") Записей не найдено!
        Alert(v-if="!requestSuccess" @close="requestSuccess = true" class-info="alert-danger") {{ errorMessage }}

        nav(v-if="this.messageCollection.length")
            ul.pagination
                li(v-for="page in pages" :key="page" :class="{active : currentPage === page}")
                    a.page(href="#" @click.prevent="getPage(page)") {{ page }}
        ProgressBar(v-if="loading" :progress="100" :max="100")
        div(v-if="!loading")
            div.panel.panel-info(v-for="message in messageCollection" :key="message.id")
                div.panel-heading
                    h3.panel-title Имя: {{ message.name }}, Email: {{ message.email }}
                div.panel-body {{ message.message }}
                div.panel-footer.text-muted сообщение добавлено в {{ message.createdAt }} (id={{ message.id }})
                ul.nav.nav-pills
                    li.presentation
                        a.text-success(:href="`/feedback/edit-form?id=${message.id}`") редактировать
                    li.presentation
                        a.text-danger(href="#" @click.stop="deleteMessage(message.id)") удалить
</template>

<script>
import Api from "@/Service/Api";
import Alert from "@/Components/Alert";
import ProgressBar from "@/Components/ProgressBar";

export default {
    components: {Alert, ProgressBar},
    data: () => ({
        loading: false,
        requestSuccess: true,
        errorMessage: "",
        messageCollection: [],
        currentPage: 0,
        pages: [],
    }),
    methods: {
        async loadMessageOnPage(page) {
            const messageCollectionApiDto = await Api.getMessagesOnPage(page);
            this.requestSuccess = messageCollectionApiDto.success;
            this.errorMessage = messageCollectionApiDto.message;
            if (messageCollectionApiDto.success) {
                this.messageCollection = messageCollectionApiDto.messageCollection;
            }
        },
        async loadPages() {
            const pagesApiDto = await Api.getPages();
            this.requestSuccess = pagesApiDto.success;
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
            if (this.requestSuccess && this.messageCollection.length) {
                this.currentPage = page;
                location.hash = `#page=${page}`;
            }
            this.loading = false;
        },
        async deleteMessage(id) {
            this.loading = true;
            const responseApiDto = await Api.deleteMessage(id);
            this.requestSuccess = responseApiDto.success;
            this.errorMessage = responseApiDto.message;
            if (responseApiDto.success) {
                await this.loadPages();
                await this.loadMessageOnPage(this.currentPage);
                if (this.requestSuccess && this.messageCollection.length === 0) {
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
        if (this.requestSuccess) {
            let defaultStartPage = 1;
            const regResult = /page=(\d+)/.exec(location.hash);
            const pageNumberFromParam = regResult ? parseInt(regResult[1]) : defaultStartPage;
            if (pageNumberFromParam > 0 && pageNumberFromParam <= this.pages.length) {
                defaultStartPage = pageNumberFromParam;
            }
            this.currentPage = defaultStartPage;
            this.loadMessageOnPage(defaultStartPage);
        }
        this.loading = false;
    }
}
</script>
