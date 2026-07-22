var DBInputMes = /** @class */ (function() {
    function DBInputMes(element) {
        this.element = element;
        this.addListeners();
    }

    DBInputMes.prototype.addListeners = function() {
        var self = this;
        this._callbackOnValid = () => {};
        this._callbackOnInvalid = () => {};
        this.element.addEventListener('input',
            function() { return DBInputMes.validate(self); });
    };
    DBInputMes.validate = function(input) {
        if (isNaN(input.element.value)) {
            input.element.value = '';
            input._callbackOnInvalid();
            return false;
        }
        if (input.element.value.length > 2) {
            input.element.value = input.element.value.substr(0, 2);
            input._callbackOnInvalid();
            return false;
        }
        var value = Number(input.element.value);
        if (value > 12 || value < 1) {
            input.element.value = '';
            input._callbackOnInvalid();
            return false;
        }
        if (input.next && input.element.value.length === 2) {
            input.next.focus();
        }
        input._callbackOnValid();
        return true;
    };
    DBInputMes.prototype.onValid = function(value) {
        this._callbackOnValid = value;
    };
    DBInputMes.prototype.onInvalid = function(value) {
        this._callbackOnInvalid = value;
    };
    return DBInputMes;
}());
