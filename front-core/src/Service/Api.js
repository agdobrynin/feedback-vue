import ky from "ky";
import ResponseApiDto from "@/Dto/ResponseApiDto";
import PagesApiDto from "@/Dto/PagesApiDto";
import MessageCollectionApiDto from "@/Dto/MessageCollectionApiDto";
import MessageApiDto from "@/Dto/MessageApiDto";

// Имя заголовка для передачи CSRF токена в хэдерах запросов
const csrfKey = "csrf-key-verifier";

const api = ky.extend({
    hooks: {
        beforeRequest: [
            (request) => {
                // добавить csrf токен, взяный в шаблоне из meta поля в html разметке
                request.headers.set(csrfKey, Api.getCsrfToken());
                request.headers.set("accept", "application/json");
            }
        ],
        afterResponse: [
            (request, options, response) => {
                Api.setCsrfToken(response.headers.get(csrfKey));
            }
        ],
    }
});

export default class Api {

    static getCsrfDomObject() {
        return document.querySelector(`meta[name="${csrfKey}"]`);
    }

    static getCsrfToken() {
        return Api.getCsrfDomObject()?.content || "";
    }

    static setCsrfToken(csrfToken) {
        if (Api.getCsrfDomObject()) {
            Api.getCsrfDomObject().content = csrfToken;
        }
    }

    static async store(messageDto) {
        const formData = new FormData();
        formData.append("id", messageDto.id);
        formData.append("name", messageDto.name);
        formData.append("email", messageDto.email);
        formData.append("message", messageDto.message);

        const responseJson = await api.post("/feedback/store", {
            body: formData
        }).then((response) => {
            return response.json();
        }).catch((error) => {
            return Api.errorResponse(error.response);
        }).then(error => error);

        return new ResponseApiDto(responseJson);
    }

    static async getMessage(id) {
        const responseJson = await api.get(`/feedback/get?id=${id}`).then((response) => {
            return response.json();
        }).catch((error) => {
            return Api.errorResponse(error.response);
        }).then(error => error);

        return new MessageApiDto(responseJson);
    }

    static async getMessagesOnPage(page) {
        const formData = new FormData();
        formData.append("page", page);
        const responseJson = await api.post("/feedback-list/get", {
            body: formData
        }).then((response) => {
            return response.json();
        }).catch((error) => {
            return Api.errorResponse(error.response);
        }).then(error => error);

        return new MessageCollectionApiDto(responseJson);
    }

    static async getPages() {
        const responseJson = await api.post("/feedback-list/pages").then((response) => {
            return response.json();
        }).catch((error) => {
            return Api.errorResponse(error.response);
        }).then(error => error);

        return new PagesApiDto(responseJson);
    }

    static async deleteMessage(id) {
        const formData = new FormData();
        formData.append("id", id);
        const responseJson = await api.post("/feedback/delete", {
            body: formData
        }).then((response) => {
            return response.json();
        }).catch((error) => {
            return Api.errorResponse(error.response);
        }).then(error => error);

        return new ResponseApiDto(responseJson);
    }

    static async errorResponse(response) {
        const contentType = response.headers.get("content-type");
        if (contentType && contentType.indexOf("application/json") !== -1) {
            return response.json();
        } else {
            return {success: false, message: await response.text()};
        }
    }
}
