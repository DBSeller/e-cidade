<?php

use Classes\PostgresMigration;

class M11641R2020ServicosPrestados extends PostgresMigration
{

    public function up()
    {
        $this->formulario();
        $this->dicionario();
        $this->estrutura();
        $this->menu();
    }

    private function formulario()
    {
        $this->execute("
          insert into esocialformulariotipo values (27, 'R-2020 - Retenção Contribuição Previdenciária - Serviços Prestados');
        ");

        $this->execute("
            insert into avaliacao( db101_sequencial ,db101_avaliacaotipo ,db101_descricao ,db101_identificador ,db101_obs ,db101_ativo ,db101_cargadados ,db101_permiteedicao ) values ( 3000040 ,8 ,'R-2020 - Retenção Contribuição Previdenciária - Serviços Prestados' ,'r2020-retencao-contribuicao-previdenciaria-servico' ,'R-2020 - Retenção Contribuição Previdenciária - Serviços Prestados' ,'true' ,'' ,'false' );
            insert into avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ,db102_ordem ) values ( 3000541 ,3000040 ,'Registro que identifica o estabelecimento \"prestador\" de serviços mediante cessão de mão de obra.' ,'registro-que-identifica-o-estabelecimento-prestado' ,'ideEstabPrest' ,1 );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002430 ,1 ,3000541 ,'Tipo de inscricao' ,'tipo-de-inscricao5c23713025854' ,'true' ,'true' ,1 ,1 ,'' ,0 ,'false' ,'' ,'tpInscEstabPrest' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002430;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001055 ,3002430 ,'CNPJ' ,'cnpj5c237130298da' ,'false' ,0 ,'1' ,'tpInscEstabPrest_1' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002431 ,2 ,3000541 ,'Informar o número de inscrição do contribuinte (CNPJ).' ,'informar-o-numero-de-inscricao-do-contribuinte-cnp' ,'true' ,'true' ,2 ,3 ,'' ,0 ,'false' ,'' ,'nrInscEstabPrest' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002431;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001056 ,3002431 ,'' ,'5c237130324be' ,'true' ,0 ,'' ,'nrInscEstabPrest' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002432 ,2 ,3000541 ,'Informar o ano/mês de referência das informações no formato AAAA-MM.' ,'informar-o-anomes-de-referencia-das-informacoes-no' ,'true' ,'true' ,3 ,1 ,'' ,0 ,'false' ,'' ,'perApur' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002432;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001057 ,3002432 ,'' ,'5c23713036a9d' ,'true' ,0 ,'' ,'perApur' );
            insert into avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ,db102_ordem ) values ( 3000542 ,3000040 ,'Identificação dos tomadores dos serviços' ,'identificacao-dos-tomadores-dos-servicos' ,'ideTomador' ,2 );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002433 ,1 ,3000542 ,'Selecione o tipo de inscrição.' ,'selecione-o-tipo-de-inscricao5c2371303a49d' ,'true' ,'true' ,1 ,1 ,'' ,0 ,'false' ,'' ,'tpInscTomador' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002433;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001058 ,3002433 ,'CNPJ' ,'cnpj5c2371303cd99' ,'false' ,0 ,'1' ,'tpInscTomador_1' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001059 ,3002433 ,'Judicial' ,'judicial5c2371303ea58' ,'false' ,0 ,'4' ,'tpInscTomador_4' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002434 ,2 ,3000542 ,'Indicar o número de inscrição do tomador.' ,'indicar-o-numero-de-inscricao-do-tomador' ,'true' ,'true' ,2 ,1 ,'' ,0 ,'false' ,'' ,'nrInscTomador' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002434;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001060 ,3002434 ,'' ,'5c237130426cf' ,'true' ,0 ,'' ,'nrInscTomador' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002435 ,1 ,3000542 ,'Indicativo de Prestação de Serviços em Obra de Construção Civil.' ,'indicativo-de-prestacao-de-servicos-em-obra-de-con' ,'true' ,'true' ,3 ,1 ,'' ,0 ,'false' ,'' ,'indObra' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002435;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001061 ,3002435 ,'Não é obra de construção civil ou não está sujeita a matrícula de obra' ,'nao-e-obra-de-construcao-civil-ou-nao-esta-sujeita' ,'false' ,0 ,'0' ,'indObra_0' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001062 ,3002435 ,'Obra de Construção Civil - Empreitada Total' ,'obra-de-construcao-civil-empreitada-total' ,'false' ,0 ,'1' ,'indObra_1' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001063 ,3002435 ,'Obra de Construção Civil - Empreitada Parcial.' ,'obra-de-construcao-civil-empreitada-parcial' ,'false' ,0 ,'2' ,'indObra_2' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002436 ,2 ,3000542 ,'Preencher com o valor bruto da(s) nota(s) fiscal(is).' ,'preencher-com-o-valor-bruto-das-notas-fiscalis' ,'true' ,'true' ,4 ,1 ,'' ,0 ,'false' ,'' ,'vlrTotalBruto' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002436;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001064 ,3002436 ,'' ,'5c2371304d12c' ,'true' ,0 ,'' ,'vlrTotalBruto' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002437 ,2 ,3000542 ,'Preencher com a soma da base de cálculo da retenção da contribuição previdenciária das notas fiscais emitidas para o contratante.' ,'preencher-com-a-soma-da-base-de-calculo-da-retenca' ,'true' ,'true' ,5 ,8 ,'' ,0 ,'false' ,'' ,'vlrTotalBaseRet' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002437;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001065 ,3002437 ,'' ,'5c23713050f20' ,'true' ,0 ,'' ,'vlrTotalBaseRet' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002438 ,2 ,3000542 ,'Soma do valor da retenção das notas fiscais de serviço emitidas para o contratante.' ,'soma-do-valor-da-retencao-das-notas-fiscais-de-ser' ,'true' ,'true' ,6 ,8 ,'' ,0 ,'false' ,'' ,'vlrTotalRetPrinc' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002438;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001066 ,3002438 ,'' ,'5c23713054d13' ,'true' ,0 ,'' ,'vlrTotalRetPrinc' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002439 ,2 ,3000542 ,'Soma do valor do adicional de retenção das notas fiscais.' ,'soma-do-valor-do-adicional-de-retencao-das-notas-f' ,'true' ,'true' ,7 ,8 ,'' ,0 ,'false' ,'' ,'vlrTotalRetAdic' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002439;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001067 ,3002439 ,'' ,'5c23713058aa4' ,'true' ,0 ,'' ,'vlrTotalRetAdic' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002440 ,2 ,3000542 ,'Valor da retenção principal que deixou de ser efetuada pelo contratante ou que foi depositada em juízo em decorrência da decisão judicial.' ,'valor-da-retencao-principal-que-deixou-de-ser-efet' ,'true' ,'true' ,8 ,8 ,'' ,0 ,'false' ,'' ,'vlrTotalNRetPrinc' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002440;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001068 ,3002440 ,'' ,'5c2371305c973' ,'true' ,0 ,'' ,'vlrTotalNRetPrinc' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002441 ,2 ,3000542 ,'Valor da retenção adicional que deixou de ser efetuada pelo contratante ou que foi depositada em juízo em decorrência da decisão judicial.' ,'valor-da-retencao-adicional-que-deixou-de-ser-efet' ,'true' ,'true' ,9 ,8 ,'' ,0 ,'false' ,'' ,'vlrTotalNRetAdic' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002441;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001069 ,3002441 ,'' ,'5c2371306057b' ,'true' ,0 ,'' ,'vlrTotalNRetAdic' );
            insert into avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ,db102_ordem ) values ( 3000543 ,3000040 ,'Notas Fiscais do Prestador de Serviços' ,'notas-fiscais-do-prestador-de-servicos' ,'nfs' ,3 );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002442 ,2 ,3000543 ,'Informar o número de série da nota fiscal/fatura ou do Recibo Provisório de Serviço - RPS ou de outro documento fiscal válido.' ,'informar-o-numero-de-serie-da-nota-fiscalfatura-ou' ,'true' ,'true' ,1 ,1 ,'' ,0 ,'false' ,'' ,'serie' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002442;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001070 ,3002442 ,'' ,'5c23713065278' ,'true' ,0 ,'' ,'serie' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002443 ,2 ,3000543 ,'Número da Nota Fiscal/Fatura ou outro documento fiscal válido, como Recibo Provisório de Serviço - RPS, CT-e OS, entre outros.' ,'numero-da-nota-fiscalfatura-ou-outro-documento-fis' ,'true' ,'true' ,2 ,1 ,'' ,0 ,'false' ,'' ,'numDocto' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002443;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001071 ,3002443 ,'' ,'5c23713068d4c' ,'true' ,0 ,'' ,'numDocto' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002444 ,2 ,3000543 ,'Data de Emissão da Nota Fiscal/Fatura ou do Recibo Provisório de Serviço ou de outro documento fiscal válido.' ,'data-de-emissao-da-nota-fiscalfatura-ou-do-recibo-' ,'true' ,'true' ,3 ,5 ,'' ,0 ,'false' ,'' ,'dtEmissaoNF' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002444;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001072 ,3002444 ,'' ,'5c2371306c7ca' ,'true' ,0 ,'' ,'dtEmissaoNF' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002445 ,2 ,3000543 ,'Preencher com o valor bruto da nota fiscal ou do Recibo Provisório de Serviço - RPS ou de outro documento fiscal válido.' ,'preencher-com-o-valor-bruto-da-nota-fiscal-ou-do-r' ,'true' ,'true' ,4 ,8 ,'' ,0 ,'false' ,'' ,'vlrBruto' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002445;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001073 ,3002445 ,'' ,'5c237130702cc' ,'true' ,0 ,'' ,'vlrBruto' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002446 ,2 ,3000543 ,'Observações' ,'observacoes5c23713071a58' ,'false' ,'true' ,5 ,1 ,'' ,0 ,'false' ,'' ,'obs' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002446;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001074 ,3002446 ,'' ,'5c237130741a5' ,'true' ,0 ,'' ,'obs' );
            insert into avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ,db102_ordem ) values ( 3000544 ,3000040 ,'Informações sobre os tipos de Serviços constantes da Nota Fiscal' ,'informacoes-sobre-os-tipos-de-servicos-constantes-' ,'infoTpServ' ,4 );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002447 ,2 ,3000544 ,'Informar o tipo de serviço.' ,'informar-o-tipo-de-servico' ,'true' ,'true' ,1 ,1 ,'' ,0 ,'false' ,'' ,'tpServico' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002447;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001075 ,3002447 ,'' ,'5c23713078d4b' ,'true' ,0 ,'' ,'tpServico' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002448 ,2 ,3000544 ,'Valor da Base de cálculo da retenção da contribuição previdenciária.' ,'valor-da-base-de-calculo-da-retencao-da-contribuic' ,'true' ,'true' ,2 ,8 ,'' ,0 ,'false' ,'' ,'vlrBaseRet' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002448;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001076 ,3002448 ,'' ,'5c2371307c839' ,'true' ,0 ,'' ,'vlrBaseRet' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002449 ,2 ,3000544 ,'Preencher com o valor da retenção apurada de acordo com o que determina a legislação vigente relativa aos serviços contidos na nota fiscal/fatura.' ,'preencher-com-o-valor-da-retencao-apurada-de-acord' ,'true' ,'true' ,3 ,8 ,'' ,0 ,'false' ,'' ,'vlrRetencao' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002449;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001077 ,3002449 ,'' ,'5c23713080816' ,'true' ,0 ,'' ,'vlrRetencao' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002450 ,2 ,3000544 ,'Informar o valor da retenção destacada na nota fiscal relativo aos serviços subcontratados, se houver, desde que todos os documentos envolvidos se refiram à mesma competência e ao mesmo serviço, conforme disciplina a legislação.' ,'informar-o-valor-da-retencao-destacada-na-nota-fis' ,'false' ,'true' ,4 ,8 ,'' ,0 ,'false' ,'' ,'vlrRetSub' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002450;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001078 ,3002450 ,'' ,'5c23713084503' ,'true' ,0 ,'' ,'vlrRetSub' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002451 ,2 ,3000544 ,'Valor da retenção principal que deixou de ser efetuada pelo contratante ou que foi depositada em juízo em decorrência de decisão judicial/administrativa.' ,'valor-da-retencao-principal-que-deixo5c23713085e3c' ,'false' ,'true' ,5 ,8 ,'' ,0 ,'false' ,'' ,'vlrNRetPrinc' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002451;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001079 ,3002451 ,'' ,'5c237130883bb' ,'true' ,0 ,'' ,'vlrNRetPrinc' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002452 ,2 ,3000544 ,'Valor dos Serviços prestados por segurados em condições especiais, cuja atividade permita concessão de aposentadoria especial após 15 anos de contribuição.' ,'valor-dos-servicos-prestados-por-segurados-em-cond' ,'false' ,'true' ,6 ,8 ,'' ,0 ,'false' ,'' ,'vlrServicos15' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002452;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001080 ,3002452 ,'' ,'5c2371308c2e4' ,'true' ,0 ,'' ,'vlrServicos15' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002453 ,2 ,3000544 ,'Valor dos Serviços prestados por segurados em condições especiais, cuja atividade permita concessão de aposentadoria especial após 20 anos de contribuição.' ,'valor-dos-servicos-prestados-por-segu5c2371308dd55' ,'false' ,'true' ,7 ,8 ,'' ,0 ,'false' ,'' ,'vlrServicos20' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002453;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001081 ,3002453 ,'' ,'5c23713090442' ,'true' ,0 ,'' ,'vlrServicos20' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002454 ,2 ,3000544 ,'Valor dos Serviços prestados por segurados em condições especiais, cuja atividade permita concessão de aposentadoria especial após 25 anos de contribuição.' ,'valor-dos-servicos-prestados-por-segu5c23713091d72' ,'false' ,'true' ,8 ,8 ,'' ,0 ,'false' ,'' ,'vlrServicos25' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002454;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001082 ,3002454 ,'' ,'5c23713096ecd' ,'true' ,0 ,'' ,'vlrServicos25' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002455 ,2 ,3000544 ,'Adicional apurado de retenção da nota fiscal, caso os serviços tenham sido prestados sob condições especiais que ensejem aposentadoria especial aos trabalhadores após 15, 20, ou 25 anos de contribuição.' ,'adicional-apurado-de-retencao-da-nota-fiscal-caso-' ,'false' ,'true' ,9 ,8 ,'' ,0 ,'false' ,'' ,'vlrAdicional' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002455;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001083 ,3002455 ,'' ,'5c2371309b054' ,'true' ,0 ,'' ,'vlrAdicional' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002456 ,2 ,3000544 ,'Valor da retenção adicional que deixou de ser efetuada pelo contratante ou que foi depositada em juízo em decorrência de decisão judicial/administrativa.' ,'valor-da-retencao-adicional-que-deixo5c2371309cab2' ,'false' ,'true' ,10 ,8 ,'' ,0 ,'false' ,'' ,'vlrNRetAdic' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002456;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001084 ,3002456 ,'' ,'5c2371309f40a' ,'true' ,0 ,'' ,'vlrNRetAdic' );
            insert into avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ,db102_ordem ) values ( 3000545 ,3000040 ,'Informações de processos relacionados a não retenção de contribuição previdenciária.' ,'informacoes-de-processos-relacionados-a-nao-retenc' ,'infoProcRetPr' ,5 );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002457 ,1 ,3000545 ,'Tipo de processo.' ,'tipo-de-processo' ,'false' ,'true' ,1 ,1 ,'' ,0 ,'false' ,'' ,'tpProcRetPrinc' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002457;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001085 ,3002457 ,'Administrativo' ,'administrativo5c237130a4cc2' ,'false' ,0 ,'1' ,'tpProcRetPrinc_1' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001086 ,3002457 ,'Judicial' ,'judicial5c237130a663d' ,'false' ,0 ,'2' ,'tpProcRetPrinc_2' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002458 ,2 ,3000545 ,'Informar o número do processo administrativo/judicial.' ,'informar-o-numero-do-processo-adminis5c237130a813d' ,'false' ,'true' ,2 ,1 ,'' ,0 ,'false' ,'' ,'nrProcRetPrinc' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002458;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001087 ,3002458 ,'' ,'5c237130aa822' ,'true' ,0 ,'' ,'nrProcRetPrinc' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002459 ,2 ,3000545 ,'Código do Indicativo da Suspensão, atribuído pelo contribuinte.' ,'codigo-do-indicativo-da-suspensao-at5c237130ac18f' ,'false' ,'true' ,3 ,1 ,'' ,0 ,'false' ,'' ,'codSuspPrinc' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002459;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001088 ,3002459 ,'' ,'5c237130ae7f4' ,'true' ,0 ,'' ,'codSuspPrinc' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002460 ,2 ,3000545 ,'Valor da retenção de contribuição previdenciária principal que deixou de ser efetuada em função de processo administrativo ou judicial.' ,'valor-da-retencao-de-contribuicao-previdenciaria-p' ,'false' ,'true' ,4 ,8 ,'' ,0 ,'false' ,'' ,'valorPrinc' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002460;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001089 ,3002460 ,'' ,'5c237130b266a' ,'true' ,0 ,'' ,'valorPrinc' );
            insert into avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ,db102_ordem ) values ( 3000546 ,3000040 ,'Informações de processos relacionados a não retenção de contribuição previdenciária adicional.' ,'informacoes-de-processos-relacionados5c237130b3c90' ,'infoProcRetAd' ,6 );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002461 ,1 ,3000546 ,'Tipo de processo.' ,'tipo-de-processo5c237130b50c9' ,'false' ,'true' ,1 ,1 ,'' ,0 ,'false' ,'' ,'tpProcRetAdic' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002461;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001090 ,3002461 ,'Administrativo' ,'administrativo5c237130b77cc' ,'false' ,0 ,'1' ,'tpProcRetAdic_1' );
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001091 ,3002461 ,'Judicial' ,'judicial5c237130b9185' ,'false' ,0 ,'2' ,'tpProcRetAdic_2' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002462 ,2 ,3000546 ,'Informar o número do processo administrativo/judicial.' ,'informar-o-numero-do-processo-adminis5c237130baa19' ,'false' ,'true' ,2 ,1 ,'' ,0 ,'false' ,'' ,'nrProcRetAdic' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002462;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001092 ,3002462 ,'' ,'5c237130bcd88' ,'true' ,0 ,'' ,'nrProcRetAdic' );insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002463 ,2 ,3000546 ,'Código do Indicativo da Suspensão, atribuído pelo contribuinte.' ,'codigo-do-indicativo-da-suspensao-at5c237130be5cd' ,'false' ,'true' ,3 ,1 ,'' ,0 ,'false' ,'' ,'codSuspAdic' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002463;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001093 ,3002463 ,'' ,'5c237130c07f9' ,'true' ,0 ,'' ,'codSuspAdic' );
            insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 3002464 ,2 ,3000546 ,'Valor da retenção de contribuição previdenciária principal que deixou de ser efetuada em função de processo administrativo ou judicial.' ,'valor-da-retencao-de-contribuicao-pre5c237130c1e73' ,'false' ,'true' ,4 ,8 ,'' ,0 ,'false' ,'' ,'valorAdic' );
            delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 3002464;
            insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001094 ,3002464 ,'' ,'5c237130c4318' ,'true' ,0 ,'' ,'valorAdic' );
        ");

        $this->execute("insert into efdreinfversaoformulario values (nextval('esocial.efdreinfversaoformulario_efd03_sequencial_seq'), 1.4, 3000040, 27);");
        $this->execute("update avaliacaopergunta set db103_tipo = 8 where db103_sequencial = 3002436;");
        $this->execute("update avaliacaoperguntaopcao set db104_descricao = 'CNO' where db104_sequencial = 4001059;");
    }

    private function dicionario()
    {
        $this->execute("
            insert into db_sysarquivo values (1010397, 'avaliacaogruporespostaefdr2020', 'Registros do evento R-2020 - Retenção Contribuição Previdenciária - Serviços Prestados', 'efd05', '2018-12-24', 'Registros do evento R-2020', 0, 'f', 'f', 'f', 'f' );
            insert into db_sysarqmod values (81,1010397);
            
            insert into db_syscampo 
            values (1010246,'efd05_sequencial','int4','Código','0', 'Código',10,'f','f','f',1,'text','Código'),
                   (1010247,'efd05_cgm','oid','CGM','', 'CGM',10,'f','f','f',1,'text','CGM'),
                   (1010248,'efd05_inscricaoprestadora','varchar(14)','CNPJ','', 'CNPJ',14,'f','t','f',0,'text','CNPJ'),
                   (1010249,'efd05_competencia','varchar(7)','Competência','', 'Competência',7,'f','t','f',0,'text','Competência'),
                   (1010250,'efd05_avaliacaogruporesposta','int4','Preenchimento','0', 'Preenchimento',10,'f','f','f',1,'text','Preenchimento'),
                   (1010251,'efd05_avaliacao','int4','Avaliação','0', 'Avaliação',10,'f','f','f',1,'text','Avaliação');
            
            insert into db_sysarqcamp 
            values (1010397,1010246,1,0),
                   (1010397,1010247,2,0),
                   (1010397,1010248,3,0),
                   (1010397,1010249,4,0),
                   (1010397,1010250,5,0),
                   (1010397,1010251,6,0);
            
            insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010397,1010246,1,1010246);
            insert into db_sysforkey 
            values (1010397,1010247,1,42,0),
                   (1010397,1010251,1,2980,0),
                   (1010397,1010250,1,2987,0);
                
            insert into db_sysindices 
            values (1008412,'avaliacaogruporespostaefdr2020_cgm_inscricaoprestadora_competencia_in',1010397,'1'),
                   (1008413,'avaliacaogruporespostaefdr2020_avaliacaogruporesposta_in',1010397,'0'),
                   (1008414,'avaliacaogruporespostaefdr2020_avaliacao_in',1010397,'0');
            
            insert into db_syscadind 
            values (1008412,1010247,1),
                   (1008412,1010248,2),
                   (1008412,1010249,3),
                   (1008413,1010250,1),
                   (1008414,1010251,1);
            
            insert into db_syssequencia values(1000806, 'avaliacaogruporespostaefdr2020_efd05_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
            update db_sysarqcamp set codsequencia = 1000806 where codarq = 1010397 and codcam = 1010246;
        ");
    }

    private function estrutura()
    {
        $this->execute("
            CREATE SEQUENCE avaliacaogruporespostaefdr2020_efd05_sequencial_seq INCREMENT 1 MINVALUE 1 MAXVALUE 9223372036854775807 START 1 CACHE 1;
            
            create table avaliacaogruporespostaefdr2020 (
              efd05_sequencial int4 ,
              efd05_cgm int not null,
              efd05_inscricaoprestadora varchar(14) not null,
              efd05_competencia varchar(7),
              efd05_avaliacaogruporesposta int not null,
              efd05_avaliacao int not null,
              CONSTRAINT avaliacaogruporespostaefdr2020_sequ_pk PRIMARY KEY (efd05_sequencial)
            );
                        
            alter table avaliacaogruporespostaefdr2020 add constraint avaliacaogruporespostaefdr2020_cgm_fk foreign key (efd05_cgm) references cgm;
            alter table avaliacaogruporespostaefdr2020 add constraint avaliacaogruporespostaefdr2020_avaliacao_fk foreign key (efd05_avaliacao) references avaliacao;
            alter table avaliacaogruporespostaefdr2020 add constraint avaliacaogruporespostaefdr2020_avaliacaogruporesposta_fk foreign key (efd05_avaliacaogruporesposta) references avaliacaogruporesposta;
            
            create unique index avaliacaogruporespostaefdr2020_cgm_inscricaoprestadora_competencia_in on avaliacaogruporespostaefdr2020(efd05_cgm,efd05_inscricaoprestadora,efd05_competencia);
            create  index avaliacaogruporespostaefdr2020_avaliacaogruporesposta_in on avaliacaogruporespostaefdr2020(efd05_avaliacaogruporesposta);
            create  index avaliacaogruporespostaefdr2020_avaliacao_in on avaliacaogruporespostaefdr2020(efd05_avaliacao);
        ");
    }

    private function menu()
    {
        $this->execute("
            insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228087 ,'Retenção Contribuição Previdenciária - Serviços Prestados' ,'Retenção Contribuição Previdenciária - Serviços Prestados' ,'efd04_r2020_servicos_prestados001.php' ,'1' ,'1' ,'Formulário R2020 de Retenção Contribuição Previdenciária - Serviços Prestados' ,'true' );
            insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228079 ,228087 ,4 ,228077 );
        ");
    }

    public function down()
    {
        $this->execute("delete from efdreinfversaoformulario where efd03_avaliacao = 3000040");

        $this->execute("
            create temp table x_avaliacaopergunta as 
              select db103_sequencial 
                from avaliacaopergunta 
               where db103_avaliacaogrupopergunta in (select db102_sequencial from avaliacaogrupopergunta where db102_avaliacao = 3000040);
            
            create temp table x_avaliacaoperguntaopcao as 
              select db104_sequencial 
                from avaliacaoperguntaopcao 
               where db104_avaliacaopergunta in (select db103_sequencial from x_avaliacaopergunta);
            
            delete from avaliacaoperguntaopcao where db104_avaliacaopergunta in (select db103_sequencial from x_avaliacaopergunta);
            delete from avaliacaopergunta where db103_sequencial in (select db103_sequencial from x_avaliacaopergunta);
            delete from avaliacaogrupopergunta where db102_avaliacao = 3000040;
            delete from avaliacao where db101_sequencial = 3000040;
            
            drop table x_avaliacaopergunta;
            drop table x_avaliacaoperguntaopcao;
        ");

        $this->execute(" delete from esocialformulariotipo where rh209_sequencial = 27;");

        $this->execute("
            drop table if exists avaliacaogruporespostaefdr2020 cascade;
            drop sequence if exists avaliacaogruporespostaefdr2020_efd05_sequencial_seq;
        ");

        $this->execute("
            delete from db_menu where id_item_filho = 228087 AND modulo = 228077;
            delete from db_itensmenu where id_item = 228087;
            
        ");

        $this->execute("
            delete from db_syssequencia where codsequencia = 1000806;
            delete from db_sysprikey where codarq = 1010397;
            delete from db_sysforkey where codarq = 1010397;
            delete from db_syscadind where codind in (1008412, 1008413, 1008414);
            delete from db_sysindices where codind in (1008412, 1008413, 1008414);
            delete from db_sysarqcamp where codarq = 1010397;
            delete from db_syscampo where codcam in (1010246, 1010247, 1010248, 1010249, 1010250, 1010251);
            delete from db_sysarqmod where codarq = 1010397;
            delete from db_sysarquivo where codarq = 1010397;
        ");
    }
}
