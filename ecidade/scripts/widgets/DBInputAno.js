var DBInputAno = /** @class */ (function() {
    function DBInputAno(element) {
        this.element = element;
        this.addListeners();
    }

    DBInputAno.prototype.addListeners = function() {
        var self = this;
        this.element.addEventListener('input',
            function() { return DBInputAno.validate(self); });
    };
    DBInputAno.validate = function(input) {
        if (isNaN(input.element.value)) {
            input.element.value = '';

            if (typeof input._callbackOnInvalid === 'function') {
                input._callbackOnInvalid();
            }

            return false;
        }
        if (input.element.value.length > 4) {
            input.element.value = input.element.value.substr(0, 4);

            if (typeof input._callbackOnInvalid === 'function') {
                input._callbackOnInvalid();
            }

            return false;
        }
        var value = Number(input.element.value);
        if (value < 1900 && input.element.value.length === 4) {
            input.element.value = '';

            if (typeof input._callbackOnInvalid === 'function') {
                input._callbackOnInvalid();
            }

            return false;
        }

        if (input.element.value.length === 4) {
            if (input.next) {
                input.next.focus();
            }

            if (typeof input._callbackOnValid === 'function') {
                input._callbackOnValid();
            }

            return true;
        }

        if (typeof input._callbackOnInvalid === 'function') {
            input._callbackOnInvalid();
        }

        return false;
    };
    DBInputAno.prototype.onValid = function(value) {
        this._callbackOnValid = value;
    };
    DBInputAno.prototype.onInvalid = function(value) {
        this._callbackOnInvalid = value;
    };
    return DBInputAno;
}());
