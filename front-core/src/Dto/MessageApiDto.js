import MessageDto from "@/Dto/MessageDto";
import ResponseApiDto from "@/Dto/ResponseApiDto";

export default class MessageApiDto extends ResponseApiDto {
    constructor(responseJson) {
        super(responseJson);
        const messageResponse = responseJson?.data['message'];
        this.messageDto = new MessageDto(
            messageResponse?.id,
            messageResponse?.name,
            messageResponse?.email,
            messageResponse?.message
        );
    }
}
