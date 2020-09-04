import ResponseApiDto from "@/Dto/ResponseApiDto";

export default class PagesApiDto extends ResponseApiDto {
    constructor(responseJson)
    {
        super(responseJson);
        this.pages = responseJson.pages || 0;
    }
}
