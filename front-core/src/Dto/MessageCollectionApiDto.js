import MessageDto from "@/Dto/MessageDto";
import ResponseApiDto from "@/Dto/ResponseApiDto";

export default class MessageCollectionApiDto extends ResponseApiDto {
    constructor(responseJson) {
        super(responseJson);
        const messages = responseJson.messageCollection || [];
        this.messageCollection = [];
        messages.forEach((message) => {
            const messageDto = new MessageDto(message.id, message.name, message.email, message.message);
            messageDto.createdAt = message.createdAt;
            this.messageCollection.push(messageDto);
        });
    }
}
