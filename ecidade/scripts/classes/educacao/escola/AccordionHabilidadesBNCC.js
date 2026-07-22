const AccordionHabilidadesBNCC = {
    modal: null,
    habilidades: [],

    generateId: function (nivel, subnivel) {
        var id = `collapse-${nivel}`;

        if (subnivel !== undefined) {
            id += `-${subnivel}`;
        }
        return id;
    },


    createConteudo: function (ul, habilidade) {
        const li = document.createElement('li');
        li.classList.add('checkbox');
        const input = document.createElement('input');
        const label = document.createElement('label');
        label.setAttribute('for', habilidade.codigo);
        label.innerText = habilidade.nome;

        input.setAttribute('id', habilidade.codigo);
        input.setAttribute('type', 'checkbox');
        input.addClassName('habilidadeCheckbox');
        input.value = habilidade.codigo;

        li.append(input);
        li.append(label);
        ul.append(li);
        return ul;
    },

    createUl: function (classNivel, nivel, subnivel) {
        const id = this.generateId(nivel, subnivel);
        const ul = document.createElement('ul');
        ul.addClassName(` ${classNivel} collapsed `);
        ul.setAttribute('id', id);
        return ul;
    },

    createLi: function (description, nivel, subnivel, ulLv) {
        const li = document.createElement('li');
        const a = document.createElement('a');
        const id = this.generateId(nivel, subnivel);

        a.setAttribute('href', `#${id}`);
        a.setAttribute('aria-expanded', 'false');
        a.addClassName(' link-paddind ellipsis');
        a.innerHTML = `${description} <i class="fas fa-plus"></i>`;


        li.append(a);
        li.append(ulLv);
        return li;
    },

    buscarHabilidades: async function (codigoTurma, codigoEtapa, codigoDisciplinaBncc) {

        const formData = new FormData();
        formData.append('acao', 'buscarHabilidades');
        formData.append('disciplinaBncc', codigoDisciplinaBncc);
        formData.append('etapa', codigoEtapa);
        formData.append('turma', codigoTurma);
        formData.append('registroAula', true);
        return await HttpClient.post('edu4_habilidades_bncc.RPC.php', {body: formData}).then(response => {

            if (response.erro) {
                alert(response.mensagem);
                return;
            }

            return response.habilidades;
        });
    },

    montaHtml: function (habilidades, elemento) {
        this.divAccordion = document.createElement('div');
        this.ulNivel1 = document.createElement('ul');

        const self = this;
        habilidades.map(function (nivel_1) {

            const indexNivel1 = identificacaoUnica();

            const ulNivel2 = self.createUl('nivel_2', indexNivel1);
            nivel_1.nivel_2.map(function (nivel_2) {

                const indexNivel2 = identificacaoUnica();
                const ulNivel3 = self.createUl('nivel_3', indexNivel1, indexNivel2);
                nivel_2.nivel_3.map(function (habilidade, indexNivel3) {
                    if (habilidade.nivel_4) {
                        const ulNivel4 = self.createUl('nivel_4', indexNivel2, indexNivel3);
                        habilidade.nivel_4.map(function (habilidadeReferencial) {
                            self.createConteudo(ulNivel4, habilidadeReferencial);
                        });
                        const listaLv3 = self.createLi(habilidade.nome, indexNivel2, indexNivel3, ulNivel4);
                        ulNivel3.append(listaLv3);
                    } else {
                        self.createConteudo(ulNivel3, habilidade);
                    }
                });

                const listaLv2 = self.createLi(nivel_2.nome, indexNivel1, indexNivel2, ulNivel3);
                ulNivel2.append(listaLv2);
            });
            const listaLv1 = self.createLi(nivel_1.nome, indexNivel1, undefined, ulNivel2);

            self.ulNivel1.append(listaLv1);

            self.divAccordion.append(self.ulNivel1);
        });

        elemento.innerHTML = '';
        elemento.append(self.divAccordion);
        self.divAccordion.setAttribute('id', 'accordion');
        self.ulNivel1.addClassName('nivel_1');
        self.eventosAccordion();
    },

    eventosAccordion: function () {

        const links = document.querySelectorAll('#accordion a');
        links.forEach((link) => {

            link.addEventListener('click', (event) => {
                const ref = link.getAttribute('href');
                const i = link.querySelector('i');

                const expanded = link.getAttribute('aria-expanded') === 'true' ? false : true;
                if (expanded) {
                    i.className = 'fas fa-minus';
                } else {
                    i.className = 'fas fa-plus';
                }
                link.setAttribute('aria-expanded', expanded);

                const element = document.getElementById(ref.replace('#', ''));
                element.classList.toggle("collapse");
                element.classList.toggle("collapsed");
            });
        });
    },

    build: async function (codigoTurma, codigoEtapa, codigoDisciplinaBncc, data, elemento) {
        await this.buscarHabilidades(codigoTurma, codigoEtapa, codigoDisciplinaBncc, elemento)
            .then((habilidades) => {
                this.montaHtml(habilidades, elemento);
                this.habilidades = habilidades;
                return this.habilidades;
            });
    },

    adicionarEventosCheckbox: function (callback) {

        const nodeListCheckbox = document.querySelectorAll('input.habilidadeCheckbox');
        nodeListCheckbox.forEach((checkbox) => {
            checkbox.addEventListener('click', (event) => {
                callback(event);
            });
        });
    }
};
