var CONTEXT = this;

String.prototype.formatterCPFCNPJ = function () {
  const cpf_cnpj = this.replace(/\D/g, '');
  let
    replacement = "$1.$2.$3-$4",
    regex = /(\d{3})(\d{3})(\d{3})(\d+)/
  ;
  
  if (cpf_cnpj.length == 14) {
    replacement = "$1.$2.$3/$4-$5";
    regex = /(\d{2})(\d{3})(\d{3})(\d{4})(\d+)/;
  }
  
  return cpf_cnpj.replace(new RegExp(regex, 'g'), replacement);
};

if (!CONTEXT['ADMIN']) {
  var ADMIN = 1;
}

if (!CONTEXT['ASSINANTE']) {
  var ASSINANTE = 2;
}

if (!CONTEXT['TIME_OUT_PIN']) {
  var TIME_OUT_PIN = 10; //Em minutos
}

if (!CONTEXT['HOST_ECIDADE']) {
  var HOST_ECIDADE = location.hostname + ((!!location.port) ? ':'+ location.port : '');
}

if (!CONTEXT['ECIDADE_URL']) {
  var ECIDADE_URL = location.protocol + '//' + HOST_ECIDADE
                    + location.pathname
                              .replace(/\/w\/\d+.*/g, '')
                              .replace(/\/extension.*/g , '');
}

function callbackPesquisarUsuarioGridAssinantes () {
  const f = (nome, cpf_cnpj) => {
    $('atualizar_cpf_cnpj').value  = !!cpf_cnpj ? cpf_cnpj.formatterCPFCNPJ() : '';
    $('atualizar_assinante').value = !!nome ? nome : '';
  };
  f.apply(this, arguments);
  db_pesquisa_usuariogridassinantes.hide();
}

function downloadFileEstorage(url) {
  HttpClient.get(url, {
    reportMessage : 'Obtendo arquivo do e-Storage',
    reportProgress : true
  }).then(res => {

    const fileDownloaded = base642File(
      res.data.content,
      res.data.filename,
      res.data.type
    );
    const 
      sizeWidth  = screen.availWidth,
      sizeHeight = screen.availHeight,
      wname = 'wname' + Math.floor(Math.random() * 10000)
    ;
    const W_FILE_STORAGE = window.open(
      URL.createObjectURL(fileDownloaded)
      ,wname,
      +'fullscreen=1,toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=1,resizable=0');
    W_FILE_STORAGE.moveTo(0,0)
    W_FILE_STORAGE.onload = () => W_FILE_STORAGE.onbeforeunload = () => {
      console.log('W_FILE_STORAGE - onbeforeunload');
      URL.revokeObjectURL(fileDownloaded)
    };
  }).catch(e => console.error(e));
}

function base642ByteArray (base64Data, sliceSize = 512) {
  const byteCharacters = atob(base64Data.split(',').pop());
  const byteArrays = [];

  for (let offset = 0; offset < byteCharacters.length; offset += sliceSize) {
    const slice = byteCharacters.slice(offset, offset + sliceSize);

    const byteNumbers = new Array(slice.length);
    for (let i = 0; i < slice.length; i++) {
      byteNumbers[ i ] = slice.charCodeAt(i);
    }

    const byteArray = new Uint8Array(byteNumbers);
    byteArrays.push(byteArray);
  }

  return byteArrays;
}

function base642File (base64Data, filename, contentType = 'text/plain', sliceSize = 512) { 
  return new File(
    base642ByteArray(base64Data),
    filename,
    { type : contentType }
  );
}

function base642Blob (base64Data, contentType = 'text/plain', sliceSize = 512) {
  return new Blob(
    base642ByteArray(base64Data),
    { type: contentType }
  );
}

/**
 * DBView responsável por mostrar os 
 * arquivos que necessitam de assinatura
 * a partir de um usuario logado
 */
DBViewAssinaturaDigital = function () {
  
  this.dependencies();

  this.datagridName           = `datagrid_arquivosAssinar`;
  this.datagridAssinantesName = `datagrid_atualizarassinantes`;

  this.sURL = `${ECIDADE_URL}/v4/api/configuracao`;

  this.oCollectionArquivosAssinar = null;
  this.oCollectionGridAssinantes = null;
  this.oGridArquivosAssinar = null;
  this.oGridAssinantes = null;
  this.oWindow = null;
  this.oWindowAtualizarAssinantes = null;

  this.fCallBack  = {
    close : function() {}
  }

  this.signer = SignerSDK;

  this.init();
};

DBViewAssinaturaDigital.prototype.init = function () {
  this.arquivosAssinar = new Map();

  this.liberarModoAdmin = false;
  this.iModo = ASSINANTE;

  this.usuarioLogado = {
    name : null,
    cpf_cnpj : null
  };

  this.filtro = {
    assinante : null,
    assinado  : 0,
    arquivos  : [],
  };
  this.pin = null;
};

DBViewAssinaturaDigital.prototype.getUsuarioLogado = function () {
  return this.usuarioLogado;
};

DBViewAssinaturaDigital.prototype.setUsuarioLogado = function (usuarioLogado) {
  this.usuarioLogado = usuarioLogado;
  return this;
};

DBViewAssinaturaDigital.prototype.setModo = function (modo) {
  this.iModo = modo;

  if (this.iModo == ADMIN) {
    this.liberarModoAdmin = true;
  }

  return this;
};

DBViewAssinaturaDigital.prototype.abrirArquivosSelecionados = function (usuario, arquivosSelecionados) {

  if (!(arquivosSelecionados instanceof Array)) {
    throw new Error(`Filtro de arquivos dever ser um array com inteiros com id dos arquivos.`);
  }

  if (arquivosSelecionados.length == 0) {
    throw new Error(`Filtro de arquivos não deve estar vazio.`);
  }

  this.iModo = ASSINANTE;
  this.liberarModoAdmin = false;
  this.filtro.arquivos  = arquivosSelecionados;

  this.abrir(usuario);

  const chkBoxAllElems = document.getElementById(`DBGrid.instances.${this.datagridName}SelectAll`);

  this.carregarArquivosAssinar().then(() => chkBoxAllElems.click());
};

DBViewAssinaturaDigital.prototype.abrir = function (usuario) {

  if (!!usuario && !!usuario.cpf_cnpj) {
    this.usuarioLogado = usuario;
  }

  if (!this.usuarioLogado || !this.usuarioLogado.cpf_cnpj) {
    return alert("Impossível determinar o usuário");
  }

  this.verificarInstalacaoExtensaoSDK();
  this.carregarTela();
};

DBViewAssinaturaDigital.prototype.carregarTela = function () {

  const sTitulo     = 'Documentos para assinar digitalmente';
  const sFormulario = 'con4_assinaturadigital.php';

  this.oWindow = new windowAux('AssinaturaDigital', sTitulo, 1200, 580);
  this.oWindow.zIndex = 2;
  this.oWindow.setContent('');
  this.oWindow.setShutDownFunction(function () {
    this.fechar();
  }.bind(this));
  this.oWindow.show();

  this.oWindow.getContentContainer().load(
    sFormulario,
    function () {

      const thisURL = this.sURL;
      this.oCollectionArquivosAssinar = Collection.create().setId('file_id');
      this.oGridArquivosAssinar = new DatagridCollection(this.oCollectionArquivosAssinar, this.datagridName);
      this.oGridArquivosAssinar.configure({
        order : false,
        action: {
          label: "Ações",
          width: "50px",
          align: "center"
        }
      })
      this.oGridArquivosAssinar.addColumn(
        'file_id',
        {
          label: "ID",
          width: "70px",
          align: "center"
        }
      );
      this.oGridArquivosAssinar.addColumn(
        'file_previous_version',
        {
          label: "Versão anterior",
          width: "100px",
          align: "center"
        }
      ).transform((file_previous_version, file) => {
        if (!!file.file_previous_version) {
          const urlToFile = `${thisURL}/documentos-assinar/${file.file_previous_version}?older_revision=true`;
          return `<a href=\"#\" onclick=\"downloadFileEstorage('${urlToFile}')\">${file_previous_version}</a>`;
        }

        return '';
      });

      this.oGridArquivosAssinar.addColumn(
        'document_type',
        {
          label: "Tipo Documento",
          width: "110px",
          align: "center"
        }
      ).transform((document_type, file) => {
        if (!!file.file_metadata) {
          var metadata = JSON.parse(file.file_metadata);

          return metadata.tipo_documento;
        }

        return '';
      });

      this.oGridArquivosAssinar.addColumn(
        'number',
        {
          label: "Número",
          width: "90px",
          align: "center"
        }
      ).transform((number, file) => {
        if (!!file.file_metadata) {
          var metadata = JSON.parse(file.file_metadata);

          return metadata.numero;
        }

        return '';
      });

      this.oGridArquivosAssinar.addColumn(
        'file_name',
        {
          label: "Nome",
          width: "200px"
        }
      ).transform((file_name, file) => {
        let urlToFile = file.file_url;

        if (!urlToFile) {
          urlToFile = `${thisURL}/documentos-assinar/${file.file_id}`;
          return `<a href=\"#\" onclick=\"downloadFileEstorage('${urlToFile}')\">${file_name}</a>`;
        }

        return `<a target=\"_blank\" href=\"${urlToFile}\">${file_name}</a>`;
      });
      this.oGridArquivosAssinar.addColumn(
        'file_metadata',
        {
          label: "Dados",
          width: "300px"
        }
      ).transform(str => !!str ? '...' : '');
      this.oGridArquivosAssinar.addAction('A', 'Alterar assinantes', (ev, itemCollection) => {

        if (this.iModo == ASSINANTE) {
          return alert("Somente administrador pode alterar os assinantes do arquivo");
        }

        this.alterarAssinantesArquivo(itemCollection.ID);
      });
      this.oGridArquivosAssinar.addAction('E', 'Excluir assinante', (ev, itemCollection) => {

        if (this.iModo == ASSINANTE) {
          return alert("Somente administrador pode remover assinantes do arquivo");
        }

        const assinanteRemover = {
          cpf_cnpj : $F('cpf_cnpj').replace(/\D/g, '')
        }

        this.removerAssinanteArquivo(itemCollection.ID, assinanteRemover);
      });
      this.oGridArquivosAssinar.grid.setCheckbox(0);
      this.oGridArquivosAssinar.grid.setCallbackSingle((checkbox, idRow, row) => {

        if (row.isSelected) {
          this.arquivosAssinar.set(row.itemCollection.ID, row.itemCollection)
        } else {
          this.arquivosAssinar.delete(row.itemCollection.ID);
        }
      });
      this.oGridArquivosAssinar.show($('gridArquivosAssinar'));

      this.setarEventosBotoes();
      
      if (this.iModo == ASSINANTE || !this.liberarModoAdmin) {
        this.modoAssinante();

        if (!this.liberarModoAdmin) {
          $('btnAlterarModo').setAttribute('disabled', true);
        }
      }

      if (this.iModo == ADMIN) {
        this.modoAdmin();
      }

    }.bind(this)
  );
};

DBViewAssinaturaDigital.prototype.carregarTelaAtualizarAssinantes = function (file_id, assinantes) {
  const sTitulo     = 'Assinantes';
  const sFormulario = 'con4_assinaturadigital_atualizarassinantes.php';

  this.oWindowAtualizarAssinantes = new windowAux('AtualizarAssinantes', sTitulo, 800, 580);
  this.oWindowAtualizarAssinantes.zIndex = 3;
  this.oWindowAtualizarAssinantes.setContent('');
  this.oWindowAtualizarAssinantes.setShutDownFunction(function () {
    this.fecharTelaAtualizarAssinantes();
  }.bind(this));
  this.oWindowAtualizarAssinantes.show(null, null, true);

  this.oWindowAtualizarAssinantes.getContentContainer().load(
    sFormulario,
    function () {
      const thisURL = this.sURL;
      this.oCollectionGridAssinantes = Collection.create().setId('cpf_cnpj');
      this.oGridAssinantes = new DatagridCollection(this.oCollectionGridAssinantes, this.datagridAssinantesName);
      this.oGridAssinantes.configure({
        order : false,
        action: {
          label: "Ações",
          width: "80px",
          align: "center"
        }
      });
      this.oGridAssinantes.addColumn(
        'name',
        {
          label: "Nome",
          width: "350px"
        }
      );
      this.oGridAssinantes.addColumn(
        'cpf_cnpj',
        {
          label: "CPF/CNPJ",
          width: "150px",
          align: "center"
        }
      ).transform(cpf_cnpj => cpf_cnpj.formatterCPFCNPJ());
      this.oGridAssinantes.addAction('E', 'Excluir', function(ev, itemCollection) {
        this.removerGridAssinantes.call(this, file_id, itemCollection);
      }.bind(this));

      this.oGridAssinantes.show($('gridAssinantes'));

      this.setarEventosBotoesGridAssinantes(file_id);
    }.bind(this)
  );

  assinantes.forEach(assinante => this.oCollectionGridAssinantes.add(assinante));
  this.oGridAssinantes.reload();
};

DBViewAssinaturaDigital.prototype.setarEventosBotoes = function () {
  $('btnAlterarModo').observe('click', this.alterarModo.bind(this));
  $('btnProcurar').observe('click', this.procurar.bind(this));
  $('btnAssinar').observe('click', this.btnAssinar.bind(this));
  $('btnUpload').observe('click', this.novoDocumento.bind(this));
  $('assinante').observe('change', this.settingCpfCnpjAssinante);
};

DBViewAssinaturaDigital.prototype.setarEventosBotoesGridAssinantes = function (file_id) {
  $('btnPesquisarUsuarioGridAssinantes').observe('click', this.pesquisarUsuarioGridAssinantes.bind(this));
  $('btnAdicionarGridAssinantes').observe('click', function () {
    this.adicionarGridAssinantes.call(this, file_id)
  }.bind(this));
};

DBViewAssinaturaDigital.prototype.alterarModo = function () {
  
  if (!this.liberarModoAdmin) {
    return alert("Modo admin não liberado para usuário");
  }

  this.oGridArquivosAssinar.collection.clear();
  this.oGridArquivosAssinar.reload();

  if (this.iModo != ASSINANTE) {
    return this.modoAssinante();
  }
  
  this.modoAdmin();
};

DBViewAssinaturaDigital.prototype.modoAssinante = function () {

  const tagParent = $('assinante').parentElement;
  tagParent.childElements().forEach(el => tagParent.removeChild(el));

  this.iModo = ASSINANTE;
  $('modoVisibilidade').innerHTML = 'ASSINANTE';
  $('btnAssinar').removeAttribute('disabled');
  $('btnUpload').setAttribute('disabled', true);

  const campoCpfCnpj = document.getElementById('cpf_cnpj');
  const campoNome = document.createElement('input');
  
  campoNome.setAttribute('disabled', true);
  campoNome.addClassName('readonly');
  campoNome.addClassName('field-size9');
  campoNome.setAttribute('id',   'assinante');
  campoNome.setAttribute('name', 'assinante');

  campoNome.value    = this.usuarioLogado.name;
  campoCpfCnpj.value = this.usuarioLogado.cpf_cnpj;

  this.settingCpfCnpjAssinante.call(campoCpfCnpj);
  this.filtro.assinante = {
    name     : this.usuarioLogado.name,
    cpf_cnpj : this.usuarioLogado.cpf_cnpj
  }

  tagParent.appendChild(campoNome);
};

DBViewAssinaturaDigital.prototype.modoAdmin = function () {

  const tagParent = $('assinante').parentElement;
  tagParent.childElements().forEach(el => tagParent.removeChild(el));
  
  this.iModo = ADMIN;
  $('modoVisibilidade').innerHTML = 'ADMIN';
  $('btnAssinar').setAttribute('disabled', true);
  $('btnUpload').removeAttribute('disabled');

  const campoCpfCnpj = document.getElementById('cpf_cnpj');
  const campoNome = document.createElement('select');
  const opcaoSelecione = document.createElement('option');
  
  opcaoSelecione.value = '';
  opcaoSelecione.innerHTML = 'Selecione';

  campoNome.appendChild(opcaoSelecione);
  campoNome.addClassName('field-size9');
  campoNome.setAttribute('id', 'assinante');
  campoNome.setAttribute('name', 'assinante');
  campoNome.addEventListener('change', this.settingCpfCnpjAssinante);
  campoNome.addEventListener('change', ev => this.settingFilterAssinante.call(this, ev.target.value));
  campoNome.addEventListener('change', ev => this.oGridArquivosAssinar.clear());

  campoCpfCnpj.value = '';
  this.filtro.assinante = null;

  tagParent.appendChild(campoNome);

  this.carregarAssinantes();
};

DBViewAssinaturaDigital.prototype.procurar = function () {
  this.filtro.assinante = {
    cpf_cnpj: $F('cpf_cnpj'),
    name: $F('assinante')
  };
  this.filtro.assinado = !!$F('assinado') || null;
  this.carregarArquivosAssinar();
};

DBViewAssinaturaDigital.prototype.btnAssinar = function () {

  if (this.arquivosAssinar.size < 1) {
    return alert("Você deve selecionar ao menos um arquivo para assinar.");
  }

  if (!!this.pin) {
    return this.assinar(this.pin);
  }

  top.alertify.prompt(
    "Informe o seu PIN.",
    (ok, pin) => ok ? (!pin ? alert("PIN não informado.") : this.settingPin(pin)) : false
  );

  const input = top.document.querySelector('.alertify-prompt input');
  if (input) {
    input.type = 'password';
    input.focus();
  }
};

DBViewAssinaturaDigital.prototype.settingPin = function (pin) {
  const fnTimeOutPIN = setTimeout(() => {
    clearTimeout(fnTimeOutPIN);
    this.pin = null;
  }, (TIME_OUT_PIN * 60 * 1000));
  this.assinar(pin);
};

DBViewAssinaturaDigital.prototype.assinar  = function (pin) {

  this.pin = pin;

  const errorMSG = "Ocorreu um erro ao obter os certificados, verifique a instalação do assinador.";
  js_divCarregando("Obtendo certificado digital", 'loading_message');

  this.getCertificados(this.pin).then(certificados => {
  
    const arquivosEnviarAssinador = [];
    const certificado = certificados[certificados.length - 1];
    const certificadoCpfCnpj = certificado.subject.replace(/.+\:(\d+)/g, '$1');
    const certificadoNome = certificado.subject.replace(/(.+?)\:(:?\d+)*/g, '$1').trim();

    js_removeObj('loading_message');

    if (this.usuarioLogado.name.trim() != certificadoNome) {
      const nomesInformados = [
        `Nome do usuario assinante: ${this.usuarioLogado.name.trim()}`,
        `Nome do certificado: ${certificadoNome}`
      ];
      return alert("O nome do proprietário do certificado e do usuário assinante são diferentes.\n\n"+ nomesInformados.join("\n"));
    }

    js_divCarregando("Assinando documentos...", 'loading_message');

    this.arquivosAssinar.forEach(arquivo => {
      const arq = {
        id: arquivo.ID,
        url: arquivo.file_url || null,
        type: arquivo.file_type,
        filename: arquivo.file_name,
        content : arquivo.content || null
      }

      if (!arquivo.file_url) {
        return arquivosEnviarAssinador.push(
          this.obterArquivoEstorage(arquivo.ID)
          .then(arquivoEstorage => {
            arq.type = arquivoEstorage.type;
            arq.filename = arquivoEstorage.filename;
            arq.content = arquivoEstorage.content;

            return this.assinarArquivo(arq, certificado);
          })
        );
      }

      arquivosEnviarAssinador.push(this.assinarArquivo(arq, certificado));
    }, this);

    Promise.allSettled(arquivosEnviarAssinador)
           .then(results => {
              const arquivosAssinadosSucesso = [];
              const arquivosErroAssinatura   = [];
              results.forEach(result => {
                switch(result.status) {
                  case 'fulfilled':
                    if (result.value && result.value.id) {
                      arquivosAssinadosSucesso.push(result.value.id);
                    };
                    break;
                  
                  case 'rejected':
                    if (result.reason) {
                      arquivosErroAssinatura.push(result.reason);
                    }
                    break;
                }
              });
              this.arquivosAssinar.clear();
              js_removeObj('loading_message');

              alert(`ASSINADO(s) ${arquivosAssinadosSucesso.length} arquivo(s) com sucesso.`);

              if (arquivosErroAssinatura.length > 0) {
                alert(`Ocorreram ERROS ao assinar os arquivos abaixo:\n\n${arquivosErroAssinatura.join("\n")}`);
              }
            });
  }).catch(e => {
    js_removeObj('loading_message');

    if (e.message) {
      return alert(errorMSG + '\n\n' + e.message);
    }

    alert("Ocorreu um erro. Verique o console do navegador.");
    console.error(e);
  });
};

DBViewAssinaturaDigital.prototype.novoDocumento = function () {
  const apiUrl             = this.sURL;
  const urlUploadDocumento = `${apiUrl}/documentos-assinar/novo-documento`;
  const signers = [];

  if (this.iModo == ADMIN) {
    if (!this.filtro.assinante || (!this.filtro.assinante.name && !this.filtro.assinante.cpf_cnpj)) {
      return alert("Selecione um assinante para fazer o upload do arquivo para assinar.");
    }
    signers.push({
      name     : this.filtro.assinante.name,
      cpf_cnpj : this.filtro.assinante.cpf_cnpj
    })
  } else {
    signers.push({
      name     : this.usuarioLogado.name,
      cpf_cnpj : this.usuarioLogado.cpf_cnpj
    });
  }

  const elInputFile = document.createElement('input');
  elInputFile.style.display = 'none';
  elInputFile.setAttribute('type', 'file');
  elInputFile.setAttribute('name', 'upload-file');
  elInputFile.setAttribute('id',   'upload-file');
  elInputFile.addEventListener('change', ev => {
    const signersPersons = new Map();
    signersPersons.set('signers', signers);
    js_divCarregando("Salvando novo documento...", 'loading_message');
    this.salvarArquivo(urlUploadDocumento, ev.target.files, signersPersons)
    .then(() => js_removeObj('loading_message'))
    .then(() => this.carregarArquivosAssinar())
    .catch(e => {
      js_removeObj('loading_message');
      console.error(e);
    });
  })

  elInputFile.click();
};

DBViewAssinaturaDigital.prototype.salvarArquivo = function (url, files, signers, file_father = null) {
  const fs = [];
  const getContent = (data) => {
    return new Promise((resolve, reject) => {
      const reader = new FileReader();
      reader.addEventListener('loadend', () => {
        resolve({
          id: null,
          nome: data.name,
          filename: data.name,
          conteudo: reader.result,
          content: reader.result.split(',').pop(),
          type: data.type,
        });
      });
      reader.addEventListener('error', (err) => reject(err));
      reader.readAsDataURL(data);
    });
  };

  if (files instanceof File) {
    fs.push(getContent(files));
  } else {
    Array.from(files).forEach(file => {
      fs.push(getContent(file));
    });
  }

  return new Promise((resolve, reject) => {
    Promise.all(fs).then(arquivos => {
      const form = new FormData();
      const filesUpload = arquivos.map((arquivo, index) => {
        form.append(
          `files[${index}]`,
          base642Blob(arquivo.content, arquivo.type),
          arquivo.filename
        );
      });
      signers.forEach((item, index) => form.append(index, JSON.stringify(item)));

      if (!!file_father) {
        form.append("file_father", file_father);
      }

      HttpClient.post(url, {
        body : form,
        reportProgress: false
      }).then(res => resolve(res.data))
    }).catch(e => reject(e));
  });
};

DBViewAssinaturaDigital.prototype.obterArquivoEstorage = function (idArquivo) {
  return new Promise((resolve, reject) => {
    const url = `${this.sURL}/documentos-assinar/${idArquivo}`;
    HttpClient.get(url, { reportProgress: false })
    .then(response => resolve(response.data))
    .catch(e => reject(e));
  });
};

DBViewAssinaturaDigital.prototype.assinarArquivo = function(arquivo, certificate) {
  return new Promise((resolve, reject) => {
    const tipoArquivo = 'pdf';

    if (arquivo.type.indexOf(tipoArquivo) < 0) {
      reject(`\nDevem ser assinados apenas arquivos do tipo ${tipoArquivo}.`
        + `\nArquivo (${arquivo.id}) do tipo: ${arquivo.type}`);
    }

    const signature = new SignerSDK.Signature();
          signature.certificate = certificate;

    this.buscarAssinaturasNoArquivo(arquivo.id).then(assinaturasNoArquivo => {
      const qtdeAssinaturas = assinaturasNoArquivo.length;

      switch (qtdeAssinaturas) {
        case 0:
          signature.position.x = 10;
          break;

        case 1:
          signature.position.x = 220;
          break;
        
        case 2:
          signature.position.x = 420;
          break;
        
        default: // Quando tem 3 ou mais assinaturas
          reject(`\nO arquivo ${arquivo.id} (${arquivo.filename}) já possui o máximo (${qtdeAssinaturas}) de assinaturas.`);
          break;
      }

      const doc            = new SignerSDK.Document();
            doc.signatures = [signature];
      
      if (!!arquivo.url) {
        doc.path       = arquivo.url;
        doc.content    = fetch(arquivo.url, {
            method     : 'GET',
        }).then((res) => res.blob());
      } else {
        doc.path    = arquivo.filename;
        doc.content = Promise.resolve(base642Blob(arquivo.content, arquivo.type));
      }

      const signer = new SignerSDK.Signer([doc]);
            signer.sign(this.pin)
                  .then((response) => {
                    return new Promise(function(resolv, rej) {

                      if (response[0].status.error) return rej(false);

                      const docSigned = response[0].document;
                            docSigned.content.then(function(blob) {

                              const reader = new FileReader();
                                    reader.readAsDataURL(blob);                        
                                  
                              const on_load = function() {
                                resolv({
                                  filename: arquivo.filename,
                                  content : reader.result.split(',').pop(),
                                });
                              };

                              reader.addEventListener('loadend', on_load);
                              reader.addEventListener('error', rej);
                            })
                            .catch(function(err) {
                              rej(err);
                            });
                    });
                  })
                  .then(file => {
                    arquivo.file_signed = file;
                    this.salvarArquivoAssinado(arquivo).then(arquivoSalvo => {
                      this.oGridArquivosAssinar.collection.remove(arquivo.id);
                      this.oGridArquivosAssinar.reload();
                      resolve(arquivo);
                    });
                  })
                  .catch(error => reject(`Arquivo: ${arquivo.id} (${arquivo.url})`))

    }).catch(e => reject(`Arquivo: ${arquivo.id} (${arquivo.filename})`))
  });
};

DBViewAssinaturaDigital.prototype.salvarArquivoAssinado = function (arquivoAssinado) {
  const arquivo = base642File (
    arquivoAssinado.file_signed.content,
    arquivoAssinado.filename,
    arquivoAssinado.type
  )
  const urlSaveFileStorage = `${this.sURL}/documento-assinado`;
  const signers_signed = new Map();
  signers_signed.set('signers_signed', [this.filtro.assinante]);

  return new Promise((res, rej) => {
    this.salvarArquivo(urlSaveFileStorage, arquivo, signers_signed, arquivoAssinado.id)
    .then(arquivoSalvo => res(arquivoSalvo))
    .catch(e => rej(arquivoAssinado));
  });
};

DBViewAssinaturaDigital.prototype.getCertificados = function (pin) {
  return new Promise((resolve, reject) => {
    this.signer.getCertificates(pin).then(certs => {
      if (!certs || !(certs instanceof Array) || certs.length < 1) {
        reject("Não foi possível obter o certificado");
      }
      resolve(certs);
    }).catch(e => reject(e));
  });
};

DBViewAssinaturaDigital.prototype.settingCpfCnpjAssinante = function () {  
  $('cpf_cnpj').value = this.value.formatterCPFCNPJ();
};

DBViewAssinaturaDigital.prototype.settingFilterAssinante = function (cpf_cnpj) {

  if (!cpf_cnpj) {
    return this.filtro.assinante = null;
  }

  let name;
  
  $('assinante').childNodes.forEach(option => option.value == cpf_cnpj.replace(/\D/g, '') ? name = option.innerHTML : '' );

  this.filtro.assinante = {name, cpf_cnpj};
};

DBViewAssinaturaDigital.prototype.carregarAssinantes = function () {
  return new Promise(function(res, rej) {

    const params = [];
    const modo = this.iModo;

    if (modo == ASSINANTE) {
      params.push(`cpf_cnpj=${this.filtro.assinante.cpf_cnpj}`);
    }

    let url = this.sURL + '/usuario/assinantes';

    if (params.length > 1) {
      url += '?' + params.join('&');
    }

    HttpClient.get(url, {
      reportMessage : 'Buscando assinantes...',
      reportProgress : true
    }).then(response => {
      if(response.error == true){
        alert(response.message);
      }

      const assinantes = response.data || [{
        nome     : this.usuarioLogado.name,
        cpf_cnpj : this.usuarioLogado.cpf_cnpj
      }];

      assinantes.sort((current, next) => {
        if (!next || current.nome == next.nome) {
          return 0;
        }

        return (current.nome < next.nome) ? -1 : 1;
      });

      if (modo == ASSINANTE) {
        const inputNome    = document.getElementById('assinante');
        const inputCpfCnpj = document.getElementById('cpf_cnpj');
        assinantes.forEach(assinante => {
          inputNome.value    = assinante.nome
          inputCpfCnpj.value = assinante.cpf_cnpj
        });
        return res();
      }

      assinantes.forEach(assinante => {
        const option     = document.createElement('option');
        option.value     = assinante.cpf_cnpj.replace(/\D/g , '');
        option.innerHTML = assinante.nome;
        $('assinante').appendChild(option);
      });

      res();
    }).catch(error => {
      alert(error.message);
      console.error(error);
      rej();
    });
  }.bind(this));
};

DBViewAssinaturaDigital.prototype.carregarArquivosAssinar = function () {

  if (!this.filtro.assinante || !this.filtro.assinante.cpf_cnpj) {
    return alert('Deve ser informado ao menos um assinante.');
  }

  const params = [`cpf_cnpj=${this.filtro.assinante.cpf_cnpj.replace(/\D/g, '')}`];

  if (!!this.filtro.assinado) {
    params.push(`assinado=${this.filtro.assinado}`);
  }

  let url = this.sURL + '/documentos-assinar/assinante?' + params.join('&');

  const filtroArquivos = this.filtro.arquivos || [];

  return new Promise(function(res, rej) {
    HttpClient.get(url, {
      reportMessage : 'Buscando arquivos para assinar...',
      reportProgress : true
    }).then(function(response) {
      if(response.error == true) {
        alert(response.message);
      }

      const arquivos = response.data ? response.data : null;

      if (!arquivos) {
        return;
      }

      this.oGridArquivosAssinar.clear();
      
      arquivos.forEach(arquivo => {

        if (!!filtroArquivos.length > 0 && filtroArquivos.filter(arq => arq == arquivo.file_id).length == 0) {
          return;
        }

        this.oGridArquivosAssinar.collection.add({
          file_id                : arquivo.file_id,
          file_previous_version  : arquivo.file_previous_version,
          file_name     : arquivo.file_name,
          file_url      : arquivo.file_url,
          file_metadata : arquivo.file_metadata,
          file_type     : arquivo.file_type
        })
      });
      
      this.oGridArquivosAssinar.reload();

      this.oGridArquivosAssinar.collection.get().forEach(function(item, linha) {
        
        if (!item.file_metadata) {
          return;
        }

        const metadata = document.createTextNode(JSON.stringify(JSON.parse(item.file_metadata), null, 4));
        this.oGridArquivosAssinar.grid.setHint(linha, 6, metadata.textContent, {className: 'metadata'});
      }.bind(this));

      res();
    }.bind(this))
    .catch(error => {
      alert(error.message);
      rej();
    });
  }.bind(this));
};

DBViewAssinaturaDigital.prototype.removerAssinanteArquivo = function (file_id, signer) {
  const urlAssinantesDoArquivo = `${this.sURL}/documentos-assinar/atualizar-assinantes`;
  const signers = [];
  this.buscarAssinantesArquivo(file_id).then(assinantes => {
    
    assinantes.forEach(assinante => signer.cpf_cnpj != assinante.cpf_cnpj ? signers.push({
      name     : assinante.name, 
      cpf_cnpj : assinante.cpf_cnpj
    }) : '');

    this.atualizarAssinantesArquivo(file_id, signers).then(() => {
      this.oGridArquivosAssinar.collection.remove(file_id);
      this.oGridArquivosAssinar.reload();
    }).catch(e => {
      if (e.message) alert(e.message);
      console.error(e)
    })
  });
};

DBViewAssinaturaDigital.prototype.alterarAssinantesArquivo = function (file_id) {
  this.buscarAssinantesArquivo(file_id)
  .then(assinantes => this.carregarTelaAtualizarAssinantes(file_id, assinantes));
};

DBViewAssinaturaDigital.prototype.pesquisarUsuarioGridAssinantes = function () {
  let queryString = `func_db_usuarios.php?retorna_cpf_cnpj=true&`;
  queryString += "funcao_js=parent.callbackPesquisarUsuarioGridAssinantes";
  queryString += "|nome|z01_cgccpf";
  
  js_OpenJanelaIframe("", "db_pesquisa_usuariogridassinantes", queryString, "Pesquisar", true);
  $('Jandb_pesquisa_usuariogridassinantes').style.zIndex = 10001;
};

DBViewAssinaturaDigital.prototype.adicionarGridAssinantes = function (file_id) {
  const 
    signer = {
      name     : $F('atualizar_assinante'),
      cpf_cnpj : $F('atualizar_cpf_cnpj').replace(/\D/g, '')
    },
    signers = []
  ;

  this.oCollectionGridAssinantes.get().forEach(itemCollection => signers.push({
    name     : itemCollection.name,
    cpf_cnpj : itemCollection.cpf_cnpj
  }));

  signers.push(signer);

  this.atualizarAssinantesArquivo(file_id, signers)
      .then(() => {
        this.oCollectionGridAssinantes.add(signer)
        this.oGridAssinantes.reload()
      });
};

DBViewAssinaturaDigital.prototype.removerGridAssinantes = function (file_id, itemRemover) {
  const signers = [];
  
  this.oCollectionGridAssinantes.get().forEach(itemCollection => {

    if (itemCollection.ID == itemRemover.ID) {
      return;
    }

    signers.push({
      name     : itemCollection.name,
      cpf_cnpj : itemCollection.cpf_cnpj
    })
  });

  this.atualizarAssinantesArquivo(file_id, signers).then(() => {
    this.oCollectionGridAssinantes.remove(itemRemover.ID);
    this.oGridAssinantes.reload();
  });
};

DBViewAssinaturaDigital.prototype.atualizarAssinantesArquivo = function (file_id, signers) {
  const urlAssinantesDoArquivo = `${this.sURL}/documentos-assinar/atualizar-assinantes`;
  return new Promise((resolve, reject) => {
    const form = new FormData();
    form.append('file_id', file_id);
    form.append('signers', JSON.stringify(signers));

    HttpClient.post(urlAssinantesDoArquivo, {
      reportMessage: "Atualizando assinantes do arquivo",
      reportProgress : true,
      body : form
    }).then(res => {

      response = res.data ? res.data : res;
      
      if (response.message) alert(response.message);

      if (!!response.error) return;

      if (!!response.errors && !!response.errors.forbidden && response.errors.forbidden.length > 0) {
        return alert(response.errors.forbidden.join("\n"));
      }

      resolve();
    }).catch(e => {
      if (e.message) alert(e.message);
      reject();
    });
  });
};

DBViewAssinaturaDigital.prototype.buscarAssinantesArquivo = function (file_id) {
  const urlAssinantesDoArquivo = `${this.sURL}/documentos-assinar/documento/${file_id}/assinantes?all=true`;
  return new Promise((resolve, reject) => {
    HttpClient.get(urlAssinantesDoArquivo, {
      reportMessage: "Consultando assinantes do arquivo",
      reportProgress : true
    }).then(res => {
      const assinantes = new Map();
      res.data.forEach(item => {
        assinantes.set(item.cpf_cnpj, item);
      })
      resolve(assinantes);
    }).catch(e => reject(e))
  });
};

DBViewAssinaturaDigital.prototype.buscarAssinaturasNoArquivo = function (file_id) {
  const urlAssinaturasNoArquivo = `${this.sURL}/documentos-assinar/documento/${file_id}/assinado-por`;
  return new Promise((resolve, reject) => {
    HttpClient.get(urlAssinaturasNoArquivo, {
      reportProgress : false
    }).then(res => resolve(!!res && !!res.data ? res.data : []))
    .catch(e => reject(e));
  });
};

DBViewAssinaturaDigital.prototype.verificarInstalacaoExtensaoSDK = function () {
  const errorMSG = "Não foi possível obter a versão da extensão do assinador e da SDK."
    + "\nVerifique a sua instalação";

  this.signer.getVersion()
  .then(version => {

    if (!version || !version.extension || !version.sdk) {
      return alert(errorMSG);
    }

    console.log(
      'Extension - version:', version.extension,
      ', SDK - version:', version.sdk
    )
  }).catch(e => {
    if (e.message) {
      return alert(errorMSG + '\n\n' + e.message);
    }

    alert("Ocorreu um erro. Verique o console do navegador.");
    console.error(e);
  });
};

DBViewAssinaturaDigital.prototype.fechar = function () {
  this.fCallBack.close();
  this.init();
  this.oWindow.destroy();
};

DBViewAssinaturaDigital.prototype.fecharTelaAtualizarAssinantes = function () {
  this.oWindowAtualizarAssinantes.destroy();

  if (this.iModo == ADMIN) {
    document.getElementById('assinante').childElements().forEach(el => {
      if (!el.value) {
        return;
      }
      document.getElementById('assinante').removeChild(el)
    });
    
    document.getElementById('cpf_cnpj').value = "";

    this.carregarAssinantes();
    this.oGridArquivosAssinar.clear();
    this.filtro.assinante = null;
  }
};

DBViewAssinaturaDigital.prototype.dependencies = function() {

  if ( !CONTEXT["windowAux"] ) {
    console.log("Carregando windowAux");
    require_once('scripts/widgets/windowAux.widget.js');
  }
  
  if (!CONTEXT["DBGrid"]) {
    console.log("Carregando DBGrid")
    require_once('scripts/datagrid.widget.js');
  }
    
  console.log("Carregando DBHint.plugin");
  require_once("scripts/widgets/datagrid/plugins/DBHint.plugin.js");

  console.log("Carregando Session.js");
  require_once('scripts/session.js');

  console.log("Carregando HttpClient");
  require_once('scripts/classes/http/http.js');

  console.log("Carregando Collection");
  require_once("scripts/widgets/Collection.widget.js");

  console.log("Carregando DatagridCollection");
  require_once("scripts/widgets/DatagridCollection.widget.js");

  console.log("Carregando sdk");
  require_once("scripts/pades-signer-sdk.min.js");
};

DBViewAssinaturaDigital.prototype.obterInformacoesUsuario = function () {
  return new Promise((resolve, reject) => {
    PHPSession.loadData().then(session => {
      const itemSessao = session.data.find(sessionItem => sessionItem.name == 'DB_id_usuario');
      const idUsuario = itemSessao && itemSessao.value ? itemSessao.value : null;
      const baseUrl =  session.requestPath;
      const apiUrl  = `${baseUrl}v4/api/`;
      const url     = `${apiUrl}configuracao/usuario/${idUsuario}/assinantes`;

      if (!idUsuario) {
        throw new Error('Impossível determinar o usuario');
      }

      return { url };
    }).then(response => {
      HttpClient.get(response.url).then(res => {
        const assinante        = res.data.first();
        const modoAssinante    = !!assinante && assinante.permissao == 'ADMIN' ? 1 : 2; //Moodo Assinante
        const usuarioAssinante = {
          name     : !!assinante && !!assinante.nome     ? assinante.nome     : null,
          cpf_cnpj : !!assinante && !!assinante.cpf_cnpj ? assinante.cpf_cnpj : null
        };

        resolve({ modoAssinante, usuarioAssinante });

      }).catch(e => reject(e));
    }).catch(e => e.message ? alert(e.message) : '');
  });
};

DBViewAssinaturaDigital.create = function () {
  return new DBViewAssinaturaDigital();
};

DBViewAssinaturaDigital.build = function (arquivos = null) {
  const telaAssinaturaDigital = DBViewAssinaturaDigital.create();
  telaAssinaturaDigital.obterInformacoesUsuario().then(response => {
    telaAssinaturaDigital.setModo(response.modoAssinante);

    if (!!arquivos && arquivos instanceof Array && arquivos.length > 0) {
      telaAssinaturaDigital.abrirArquivosSelecionados(response.usuarioAssinante, arquivos);
      return telaAssinaturaDigital;
    }
   
    telaAssinaturaDigital.abrir(response.usuarioAssinante);
  });

  return telaAssinaturaDigital;
};
