(function (exports, DBInput) {
    const DBInputProtocoloMatriculaOnline = function () {

        this.type = 'protocolo';
        return DBInput.apply(this, arguments);
    };

    function validaProtocolo(event) {
        this.valid = true;

        const valor = this.getValue();
        if (valor.length < this.inputElement.size) {
            alert('Protocolo inválido.');
            this.valid = false;
            event.preventDefault();
            event.stopPropagation();
            this.inputElement.focus();
            return false;
        }
    };

    DBInputProtocoloMatriculaOnline.prototype = Object.create(DBInput.prototype, {
        '__infect' : DBInput.extend(function () {

            this.inputElement.placeholder = '____-_____-____';
            this.inputElement.size        = 13;
            this.inputElement.maxLength   = 13;

            const me = this;
            this.inputElement.observe('blur', validaProtocolo.bind(me));

            new MaskedInput(this.inputElement, "9999-99999-9999", {placeholder: '_'});
            DBInput.prototype.__infect.apply(this, arguments)
        }),
        getValue : DBInput.extend(function () {
            return this.inputElement.value.replace(/[^\d]*/g, '');
        }),

        getStringValue : DBInput.extend(function () {
            return this.inputElement.value;
        }),

        setValue : DBInput.extend(function (value) {
            value = value.toString().replace(/(\d{4})(\d{5})(\d{4})/g, "$1-$2-$3");
            DBInput.prototype.setValue.apply(this, arguments);
        }),
    });


    DBInputProtocoloMatriculaOnline.prototype.constructor = DBInputProtocoloMatriculaOnline;

    exports.DBInputProtocoloMatriculaOnline = DBInputProtocoloMatriculaOnline;
    return DBInputProtocoloMatriculaOnline;

})(this, DBInput);
