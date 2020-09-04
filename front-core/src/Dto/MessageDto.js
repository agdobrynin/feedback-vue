export default class MessageDto {
    constructor(id, name, email, message) {
        this.id = id || null;
        this.name = name || "";
        this.email = email || "";
        this.message = message || "";
        this.createdAt = null;
    }
}