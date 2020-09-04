export default class ResponseApiDto {
    constructor(responseJson) {
        this.success = responseJson.success || false;
        this.message = responseJson.message || "";
        this.data = responseJson.data || [];
        this.trace = responseJson.trace || [];
    }
}
