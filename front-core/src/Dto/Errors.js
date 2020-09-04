export default class Errors {

    errors = [];

    add(errorDto) {
        if (!this.has(errorDto.key)) {
            this.errors.push(errorDto);
        }
    }

    get(key) {
        const found = this.errors.filter((error) => key === error.key);
        if (found.length) {
            return found[0];
        }
   }

    has(key) {
        return this.errors.filter((error) => key === error.key).length;
    }

    unset(key) {
        this.errors = this.errors.filter((error) => key !== error.key);
    }

    hasErrors() {
      return this.errors.length > 0;
    }
}
