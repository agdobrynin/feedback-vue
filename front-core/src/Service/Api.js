import ky from "ky";
import ResponseApiDto from "@/Dto/ResponseApiDto";
import PagesApiDto from "@/Dto/PagesApiDto";
import MessageCollectionApiDto from "@/Dto/MessageCollectionApiDto";

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
        return Api.getCsrfDomObject().content || "";
    }

    static setCsrfToken(csrfToken) {
        Api.getCsrfDomObject().content = csrfToken;
    }

    static async store(messageDto) {
        const formData = new FormData();
        formData.append("name", messageDto.name);
        formData.append("email", messageDto.email);
        formData.append("message", messageDto.message);

        const responseJson = await api.post("/store", {
            body: formData
        }).then((response) => {
            return response.json();
        }).catch((error) => {
            return error.response.json();
        });

        return new ResponseApiDto(responseJson);
    }

    static async getMessagesOnPage(page) {
        const formData = new FormData();
        formData.append("page", page);
        const responseJson = await api.post("/feedback-get", {
            body: formData
        }).then((response) => {
            return response.json();
        }).catch((error) => {
            return error.response.json();
        });

        return new MessageCollectionApiDto(responseJson);
    }

    static async getPages() {
        const responseJson = await api.post("/feedback-pages").then((response) => {
            return response.json();
        }).catch((error) => {
            return error.response.json();
        });

        return new PagesApiDto(responseJson);
    }
}
