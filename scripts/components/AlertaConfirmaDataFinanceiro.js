class AlertaConfirmaDataFinanceiro extends HTMLElement {
    constructor()
    {
        super();

        this.attachShadow({mode: 'open'});

        let dataErx = this.hasAttribute('data') ? this.getAttribute('data') : undefined;

        let button = document.createElement('button');
        button.style.fontSize = '12pt';
        button.style.paddingLeft = '5px';
        button.style.textAlign = 'center';
        button.style.visibility = 'true';
        button.textContent = "Alterar Data";
        button.onclick = this._abrirConfig;

        let text = document.createElement('text');
        text.style.fontSize = '14pt';
        text.style.textAlign = 'center';
        text.style.paddingLeft = '20px';
        text.style.visibility = 'true';
        text.textContent = "Você está acessando o sistema nesta data: ";

        let data = document.createElement('b');
        data.style.fontSize = '12pt';
        data.style.paddingLeft = '1px';
        data.style.textAlign = 'center';
        data.style.visibility = 'true';
        data.textContent = dataErx + " ";

        let div = document.createElement('div');
        div.className = "alert alert-primary text-center"
        div.style.textAlign = "center"
        div.style.backgroundColor = 'lightblue';

        div.appendChild(text);
        div.appendChild(data);
        div.appendChild(button);
        this.shadowRoot.append(div);
    };


    _abrirConfig = () => {
        Desktop.Window.createSettingModal(CurrentWindow);
    };

}

window.customElements.define('db-alertaconfirmadatafinanceiro', AlertaConfirmaDataFinanceiro);
