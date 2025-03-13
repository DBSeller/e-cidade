<?php

use Classes\PostgresMigration;

class M11640RetencaoServicosTomadosEfdR2010 extends PostgresMigration
{
    /**
     * Change Method.
     *
     * Write your reversible migrations using this method.
     *
     * More information on writing migrations is available here:
     * http://docs.phinx.org/en/latest/migrations.html#the-abstractmigration-class
     *
     * The following commands can be used in this method and Phinx will
     * automatically reverse them when rolling back:
     *
     *    createTable
     *    renameTable
     *    addColumn
     *    renameColumn
     *    addIndex
     *    addForeignKey
     *
     * Remember to call "create()" or "update()" and NOT "save()" when working
     * with the Table class.
     */
    public function up()
    {
        $this->upDicionario();
        $this->upDDL();
        $this->populationDDL();
        $this->formularioUp();
        $this->itemMenuUp();
    }

    public function down()
    {
        $this->formularioDown();
        $this->downDDL();
        $this->downDicionario();
        $this->itemMenuDown();
    }

    public function formularioUp()
    {
        $sql = 
<<<SQL
        insert into avaliacao( db101_sequencial ,db101_avaliacaotipo ,db101_descricao ,db101_identificador ,db101_obs ,db101_ativo ,db101_cargadados ,db101_permiteedicao ) values ( 3000039 ,8 ,'R-2010 - Retenção Contribuição Previdenciária - Serviços tomados' ,'r2010-retencao-contribuicao-previdenciaria-servico' ,'Registros do evento R2010 - Retenção Contribuição Previdenciária - Serviços tomados' ,'true' ,'' ,'true' );
        insert into avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ,db102_ordem ) values ( 4000206 ,3000039 ,'Processos relacionados a não retenção de contribuição previdenciária' ,'processos-relacionados-a-nao-retencao-de-contribui' ,'infoProcRet_1' ,1 );
        insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 4000158 ,1 ,4000206 ,'Principal ou Adicional' ,'principal-ou-adicional' ,'true' ,'true' ,1 ,1 ,'' ,0 ,'false' ,'' ,'tipo_1' );
        delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000158;
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001095 ,4000158 ,'Principal' ,'principal' ,'false' ,0 ,'1' ,'tpTrib_1_1' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001096 ,4000158 ,'Adicional' ,'adicional' ,'false' ,0 ,'2' ,'tpTrib_2_1' );
        insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 4000159 ,1 ,4000206 ,'Tipo de processo' ,'tipo-de-processo5c250f185f212' ,'true' ,'true' ,2 ,1 ,'' ,0 ,'false' ,'' ,'tpProcRet_1' );
        delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000159;
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001097 ,4000159 ,'Administrativo' ,'administrativo5c250f1861cc5' ,'false' ,0 ,'1' ,'tpProcRet_1_1' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001098 ,4000159 ,'Judicial' ,'judicial5c250f1863b93' ,'false' ,0 ,'2' ,'tpProcRet_2_2' );
        insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 4000160 ,2 ,4000206 ,'Número do processo' ,'numero-do-processo5c250f18657b0' ,'true' ,'true' ,3 ,1 ,'' ,0 ,'false' ,'' ,'nrProcRet_1' );
        delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000160;
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001099 ,4000160 ,'' ,'5c250f18684dc' ,'true' ,0 ,'' ,'nrProcRet_1' );
        insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 4000161 ,2 ,4000206 ,'Valor da retenção do processo' ,'valor-da-retencao-do-processo' ,'false' ,'true' ,4 ,1 ,'' ,0 ,'false' ,'' ,'valor_1' );
        delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000161;
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001100 ,4000161 ,'' ,'5c250f186cae9' ,'true' ,0 ,'' ,'valor_1' );
        insert into avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ,db102_ordem ) values ( 4000207 ,3000039 ,'Processos relacionados a não retenção de contribuição previdenciária 2' ,'processos-relacionados-a-nao-retencao5c250f186ffa8' ,'infoProcRet_2' ,2 );
        insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 4000162 ,1 ,4000207 ,'Principal ou Adicional' ,'principal-ou-adicional5c250f1871872' ,'false' ,'true' ,1 ,1 ,'' ,0 ,'false' ,'' ,'tipo_2' );
        delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000162;
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001101 ,4000162 ,'Principal' ,'principal5c250f18742ed' ,'false' ,0 ,'1' ,'tpTrib_1_2' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001102 ,4000162 ,'Adicional' ,'adicional5c250f1875eaa' ,'false' ,0 ,'2' ,'tpTrib_2_2' );
        insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 4000163 ,1 ,4000207 ,'Tipo de processo' ,'tipo-de-processo5c250f1877a64' ,'false' ,'true' ,2 ,1 ,'' ,0 ,'false' ,'' ,'tpProcRet_2' );
        delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000163;
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001103 ,4000163 ,'Administrativo' ,'administrativo5c250f187a63d' ,'false' ,0 ,'1' ,'tpProcRet_1_2' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001104 ,4000163 ,'Judicial' ,'judicial5c250f187c3f1' ,'false' ,0 ,'2' ,'tpProcRet_2_2' );
        insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 4000164 ,2 ,4000207 ,'Número do processo' ,'numero-do-processo5c250f187e0c6' ,'false' ,'true' ,3 ,1 ,'' ,0 ,'false' ,'' ,'nrProcRet_2' );
        delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000164;
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001105 ,4000164 ,'' ,'5c250f1880e75' ,'true' ,0 ,'' ,'nrProcRet_2' );
        insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 4000165 ,2 ,4000207 ,'Valor da retenção do processo' ,'valor-da-retencao-do-processo5c250f1882a5f' ,'false' ,'true' ,4 ,1 ,'' ,0 ,'false' ,'' ,'valor_2' );
        delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000165;
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001106 ,4000165 ,'' ,'5c250f188542c' ,'true' ,0 ,'' ,'valor_2' );
        insert into avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ,db102_ordem ) values ( 4000208 ,3000039 ,'Processos relacionados a não retenção de contribuição previdenciária 3' ,'processos-relacionados-a-nao-retencao5c250f1889c6f' ,'infoProcRet_3' ,3 );
        insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 4000166 ,1 ,4000208 ,'Principal ou Adicional' ,'principal-ou-adicional5c250f188b7b0' ,'false' ,'true' ,1 ,1 ,'' ,0 ,'false' ,'' ,'tipo_3' );
        delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000166;
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001107 ,4000166 ,'Principal' ,'principal5c250f188e20a' ,'false' ,0 ,'1' ,'tpTrib_1_3' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001108 ,4000166 ,'Adicional' ,'adicional5c250f188fd91' ,'false' ,0 ,'2' ,'tpTrib_2_3' );
        insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 4000167 ,1 ,4000208 ,'Tipo de processo' ,'tipo-de-processo5c250f189184f' ,'false' ,'true' ,2 ,1 ,'' ,0 ,'false' ,'' ,'tpProcRet_3' );
        delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000167;
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001109 ,4000167 ,'Administrativo' ,'administrativo5c250f1894308' ,'false' ,0 ,'1' ,'tpProcRet_1_3' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001110 ,4000167 ,'Judicial' ,'judicial5c250f1895f94' ,'false' ,0 ,'2' ,'tpProcRet_2_3' );
        insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 4000168 ,2 ,4000208 ,'Número do processo' ,'numero-do-processo5c250f1897b5f' ,'false' ,'true' ,3 ,1 ,'' ,0 ,'false' ,'' ,'nrProcRet_3' );
        delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000168;
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001111 ,4000168 ,'' ,'5c250f189a4be' ,'true' ,0 ,'' ,'nrProcRet_3' );
        insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 4000169 ,2 ,4000208 ,'Valor da retenção do processo' ,'valor-da-retencao-do-processo5c250f189c0a5' ,'false' ,'true' ,4 ,1 ,'' ,0 ,'false' ,'' ,'valor_3' );
        delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000169;
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001112 ,4000169 ,'' ,'5c250f189ecad' ,'true' ,0 ,'' ,'valor_3' );
        insert into avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ,db102_ordem ) values ( 4000209 ,3000039 ,'Processos relacionados a não retenção de contribuição previdenciária 4' ,'processos-relacionados-a-nao-retencao5c250f18a1371' ,'infoProcRet_4' ,4 );
        insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 4000170 ,1 ,4000209 ,'Principal ou Adicional' ,'principal-ou-adicional5c250f18a2b4f' ,'false' ,'true' ,1 ,1 ,'' ,0 ,'false' ,'' ,'tipo_4' );
        delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000170;
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001113 ,4000170 ,'Principal' ,'principal5c250f18a5488' ,'false' ,0 ,'1' ,'tpTrib_1_4' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001114 ,4000170 ,'Adicional' ,'adicional5c250f18a71b1' ,'false' ,0 ,'2' ,'tpTrib_2_4' );
        insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 4000171 ,1 ,4000209 ,'Tipo de processo' ,'tipo-de-processo5c250f18a8f4d' ,'false' ,'true' ,2 ,1 ,'' ,0 ,'false' ,'' ,'tpProcRet_4' );
        delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000171;
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001115 ,4000171 ,'Administrativo' ,'administrativo5c250f18ab7c1' ,'false' ,0 ,'1' ,'tpProcRet_1_4' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001116 ,4000171 ,'Judicial' ,'judicial5c250f18ada46' ,'false' ,0 ,'2' ,'tpProcRet_2_4' );
        insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 4000172 ,2 ,4000209 ,'Número do processo' ,'numero-do-processo5c250f18af56c' ,'false' ,'true' ,3 ,1 ,'' ,0 ,'false' ,'' ,'nrProcRet_4' );
        delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000172;
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001117 ,4000172 ,'' ,'5c250f18b232d' ,'true' ,0 ,'' ,'nrProcRet_4' );
        insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 4000173 ,2 ,4000209 ,'Valor da retenção do processo' ,'valor-da-retencao-do-processo5c250f18b3f2c' ,'false' ,'true' ,4 ,1 ,'' ,0 ,'false' ,'' ,'valor_4' );
        delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000173;
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001118 ,4000173 ,'' ,'5c250f18b69f0' ,'true' ,0 ,'' ,'valor_4' );
        insert into avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ,db102_ordem ) values ( 4000210 ,3000039 ,'Processos relacionados a não retenção de contribuição previdenciária 5' ,'processos-relacionados-a-nao-retencao5c250f18b92e5' ,'infoProcRet_5' ,5 );
        insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 4000174 ,1 ,4000210 ,'Principal ou Adicional' ,'principal-ou-adicional5c250f18baf1d' ,'false' ,'true' ,1 ,1 ,'' ,0 ,'false' ,'' ,'tipo_5' );
        delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000174;
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001119 ,4000174 ,'Principal' ,'principal5c250f18bda3a' ,'false' ,0 ,'1' ,'tpTrib_1_5' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001120 ,4000174 ,'Adicional' ,'adicional5c250f18bf6ca' ,'false' ,0 ,'2' ,'tpTrib_2_5' );
        insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 4000175 ,1 ,4000210 ,'Tipo de processo' ,'tipo-de-processo5c250f18c12f4' ,'false' ,'true' ,2 ,1 ,'' ,0 ,'false' ,'' ,'tpProcRet_5' );
        delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000175;
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001121 ,4000175 ,'Administrativo' ,'administrativo5c250f18c3b27' ,'false' ,0 ,'1' ,'tpProcRet_1_5' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001122 ,4000175 ,'Judicial' ,'judicial5c250f18c5bc9' ,'false' ,0 ,'2' ,'tpProcRet_2_5' );
        insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 4000176 ,2 ,4000210 ,'Número do processo' ,'numero-do-processo5c250f18c88f5' ,'false' ,'true' ,3 ,1 ,'' ,0 ,'false' ,'' ,'nrProcRet_5' );
        delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000176;
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001123 ,4000176 ,'' ,'5c250f18cb4f2' ,'true' ,0 ,'' ,'nrProcRet_5' );
        insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 4000177 ,2 ,4000210 ,'Valor da retenção do processo' ,'valor-da-retencao-do-processo5c250f18cd16d' ,'false' ,'true' ,4 ,1 ,'' ,0 ,'false' ,'' ,'valor_5' );
        delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000177;
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001124 ,4000177 ,'' ,'5c250f18cfb88' ,'true' ,0 ,'' ,'valor_5' );
        insert into avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ,db102_ordem ) values ( 4000211 ,3000039 ,'Processos relacionados a não retenção de contribuição previdenciária 6' ,'processos-relacionados-a-nao-retencao5c250f18d1838' ,'infoProcRet_6' ,6 );
        insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 4000178 ,1 ,4000211 ,'Principal ou Adicional' ,'principal-ou-adicional5c250f18d4238' ,'false' ,'true' ,1 ,1 ,'' ,0 ,'false' ,'' ,'tipo_6' );
        delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000178;
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001125 ,4000178 ,'Principal' ,'principal5c250f18d6c2c' ,'false' ,0 ,'1' ,'tpTrib_1_6' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001126 ,4000178 ,'Adicional' ,'adicional5c250f18d8694' ,'false' ,0 ,'2' ,'tpTrib_2_6' );
        insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 4000179 ,1 ,4000211 ,'Tipo de processo' ,'tipo-de-processo5c250f18da110' ,'false' ,'true' ,2 ,1 ,'' ,0 ,'false' ,'' ,'tpProcRet_6' );
        delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000179;
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001127 ,4000179 ,'Administrativo' ,'administrativo5c250f18dcb5a' ,'false' ,0 ,'1' ,'tpProcRet_1_6' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001128 ,4000179 ,'Judicial' ,'judicial5c250f18de56e' ,'false' ,0 ,'2' ,'tpProcRet_2_6' );
        insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 4000180 ,2 ,4000211 ,'Número do processo' ,'numero-do-processo5c250f18dff58' ,'false' ,'true' ,3 ,1 ,'' ,0 ,'false' ,'' ,'nrProcRet_6' );
        delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000180;
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001129 ,4000180 ,'' ,'5c250f18e28de' ,'true' ,0 ,'' ,'nrProcRet_6' );
        insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 4000181 ,2 ,4000211 ,'Valor da retenção do processo' ,'valor-da-retencao-do-processo5c250f18e440f' ,'false' ,'true' ,4 ,1 ,'' ,0 ,'false' ,'' ,'valor_6' );
        delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000181;
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001130 ,4000181 ,'' ,'5c250f18e6d4a' ,'true' ,0 ,'' ,'valor_6' );
        insert into avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ,db102_ordem ) values ( 4000212 ,3000039 ,'Processos relacionados a não retenção de contribuição previdenciária 7' ,'processos-relacionados-a-nao-retencao5c250f18e8939' ,'infoProcRet_7' ,7 );
        insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 4000182 ,1 ,4000212 ,'Principal ou Adicional' ,'principal-ou-adicional5c250f18ea48f' ,'false' ,'true' ,1 ,1 ,'' ,0 ,'false' ,'' ,'tipo_7' );
        delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000182;
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001131 ,4000182 ,'Principal' ,'principal5c250f18ecd99' ,'false' ,0 ,'1' ,'tpTrib_1_7' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001132 ,4000182 ,'Adicional' ,'adicional5c250f18eea8f' ,'false' ,0 ,'2' ,'tpTrib_2_7' );
        insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 4000183 ,1 ,4000212 ,'Tipo de processo' ,'tipo-de-processo5c250f18f052d' ,'false' ,'true' ,2 ,1 ,'' ,0 ,'false' ,'' ,'tpProcRet_7' );
        delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000183;
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001133 ,4000183 ,'Administrativo' ,'administrativo5c250f18f2db3' ,'false' ,0 ,'1' ,'tpProcRet_1_7' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001134 ,4000183 ,'Judicial' ,'judicial5c250f190083b' ,'false' ,0 ,'2' ,'tpProcRet_2_7' );
        insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 4000184 ,2 ,4000212 ,'Número do processo' ,'numero-do-processo5c250f19023de' ,'false' ,'true' ,3 ,1 ,'' ,0 ,'false' ,'' ,'nrProcRet_7' );
        delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000184;
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001135 ,4000184 ,'' ,'5c250f190612c' ,'true' ,0 ,'' ,'nrProcRet_7' );
        insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 4000185 ,2 ,4000212 ,'Valor da retenção do processo' ,'valor-da-retencao-do-processo5c250f1907b6b' ,'false' ,'true' ,4 ,1 ,'' ,0 ,'false' ,'' ,'valor_7' );
        delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000185;
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001136 ,4000185 ,'' ,'5c250f190a53f' ,'true' ,0 ,'' ,'valor_7' );
        insert into avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ,db102_ordem ) values ( 4000213 ,3000039 ,'Processos relacionados a não retenção de contribuição previdenciária 8' ,'processos-relacionados-a-nao-retencao5c250f190bfe5' ,'infoProcRet_8' ,8 );
        insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 4000186 ,1 ,4000213 ,'Principal ou Adicional' ,'principal-ou-adicional5c250f190d6b0' ,'false' ,'true' ,1 ,1 ,'' ,0 ,'false' ,'' ,'tipo_8' );
        delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000186;
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001137 ,4000186 ,'Principal' ,'principal5c250f19119a5' ,'false' ,0 ,'1' ,'tpTrib_1_8' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001138 ,4000186 ,'Adicional' ,'adicional5c250f19134fb' ,'false' ,0 ,'2' ,'tpTrib_2_8' );
        insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 4000187 ,1 ,4000213 ,'Tipo de processo' ,'tipo-de-processo5c250f191510c' ,'false' ,'true' ,2 ,1 ,'' ,0 ,'false' ,'' ,'tpProcRet_8' );
        delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000187;
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001139 ,4000187 ,'Administrativo' ,'administrativo5c250f19179f5' ,'false' ,0 ,'1' ,'tpProcRet_1_8' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001140 ,4000187 ,'Judicial' ,'judicial5c250f19195bf' ,'false' ,0 ,'2' ,'tpProcRet_2_8' );
        insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 4000188 ,2 ,4000213 ,'Número do processo' ,'numero-do-processo5c250f191b51d' ,'false' ,'true' ,3 ,1 ,'' ,0 ,'false' ,'' ,'nrProcRet_8' );
        delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000188;
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001141 ,4000188 ,'' ,'5c250f191e176' ,'true' ,0 ,'' ,'nrProcRet_8' );
        insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 4000189 ,2 ,4000213 ,'Valor da retenção do processo' ,'valor-da-retencao-do-processo5c250f191fe77' ,'false' ,'true' ,4 ,1 ,'' ,0 ,'false' ,'' ,'valor_8' );
        delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000189;
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001142 ,4000189 ,'' ,'5c250f1922c2b' ,'true' ,0 ,'' ,'valor_8' );
        insert into avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ,db102_ordem ) values ( 4000214 ,3000039 ,'Processos relacionados a não retenção de contribuição previdenciária 9' ,'processos-relacionados-a-nao-retencao5c250f19248c5' ,'infoProcRet_9' ,9 );
        insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 4000190 ,1 ,4000214 ,'Principal ou Adicional' ,'principal-ou-adicional5c250f1926123' ,'false' ,'true' ,1 ,1 ,'' ,0 ,'false' ,'' ,'tipo_9' );
        delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000190;
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001143 ,4000190 ,'Principal' ,'principal5c250f1928d8e' ,'false' ,0 ,'1' ,'tpTrib_1_9' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001144 ,4000190 ,'Adicional' ,'adicional5c250f192a908' ,'false' ,0 ,'2' ,'tpTrib_2_9' );
        insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 4000191 ,1 ,4000214 ,'Tipo de processo' ,'tipo-de-processo5c250f192c53b' ,'false' ,'true' ,2 ,1 ,'' ,0 ,'false' ,'' ,'tpProcRet_9' );
        delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000191;
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001145 ,4000191 ,'Administrativo' ,'administrativo5c250f192fb74' ,'false' ,0 ,'1' ,'tpProcRet_1_9' );
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001146 ,4000191 ,'Judicial' ,'judicial5c250f19316b2' ,'false' ,0 ,'2' ,'tpProcRet_2_9' );
        insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 4000192 ,2 ,4000214 ,'Número do processo' ,'numero-do-processo5c250f193324f' ,'false' ,'true' ,3 ,1 ,'' ,0 ,'false' ,'' ,'nrProcRet_9' );
        delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000192;
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001147 ,4000192 ,'' ,'5c250f1935e12' ,'true' ,0 ,'' ,'nrProcRet_9' );
        insert into avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ) values ( 4000193 ,2 ,4000214 ,'Valor da retenção do processo' ,'valor-da-retencao-do-processo5c250f193778f' ,'false' ,'true' ,4 ,1 ,'' ,0 ,'false' ,'' ,'valor_9' );
        delete from avaliacaoperguntadb_formulas where eso01_avaliacaopergunta = 4000193;
        insert into avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001148 ,4000193 ,'' ,'5c250f1939f4b' ,'true' ,0 ,'' ,'valor_9' );
        
        insert into esocialformulariotipo values(26, 'R-2010 - Retenção Contribuição Previdenciária - Serviços tomados');
        insert into efdreinfversaoformulario values(12, '1.4', 3000039, 26);
        
        insert into db_sysarquivo values (1010364, 'avaliacaogruporespostaretservicostomados', 'Tabela para envio do arquivo R-2010 do EFD Reinf', '', '2018-12-21', 'Retenção de serviços tomados', 0, 'f', 'f', 'f', 'f' );
        insert into db_sysarqmod values (81,1010364);
        insert into db_syscampo values(1010237,'efd04_sequencial','int4','Campo sequencial','0', 'Sequencial',1,'f','f','f',1,'text','');
        insert into db_syscampo values(1010238,'efd04_avaliacaogruporesposta','int4','Avaliação grupo resposta','0', 'Avaliacao Gupo Resposta',1,'f','f','f',1,'text','');
        insert into db_syscampo values(1010239,'efd04_cgmcontribuinte','int4','CGM do contribuinte','0', 'Cgm do contribuinte',1,'f','f','f',1,'text','Cgm do contribuinte');
        insert into db_syscampo values(1010240,'efd04_cgmprestador','int4','CGM do prestador de serviço','0', 'Cgm Prestador',1,'f','f','f',1,'text','Cgm Prestador');
        insert into db_syscampo values(1010241,'efd04_ano','int4','Ano da competencia','0', 'ano',1,'f','f','f',1,'text','ano');
        insert into db_syscampo values(1010242,'efd04_mes','int4','Mês da competência','0', 'Mes',1,'f','f','f',1,'text','Mes');
        delete from db_sysarqcamp where codarq = 1010364;
        insert into db_sysarqcamp values(1010364,1010237,1,0);
        insert into db_sysarqcamp values(1010364,1010238,2,0);
        insert into db_sysarqcamp values(1010364,1010239,3,0);
        insert into db_sysarqcamp values(1010364,1010240,4,0);
        insert into db_sysarqcamp values(1010364,1010241,5,0);
        insert into db_sysarqcamp values(1010364,1010242,6,0);
        delete from db_sysprikey where codarq = 1010364;
        insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010364,1010237,1,1010237);

        CREATE SEQUENCE avaliacaogruporespostaretencaoservicostomados_efd04_sequencial_seq
        INCREMENT 1
        MINVALUE 1
        MAXVALUE 9223372036854775807
        START 1
        CACHE 1;

        CREATE TABLE esocial.avaliacaogruporespostaretencaoservicostomados(
            efd04_sequencial int primary key default nextval('avaliacaogruporespostaretencaoservicostomados_efd04_sequencial_seq'),
            efd04_avaliacaogruporesposta int not null,
            efd04_cgmcontribuinte int not null,
            efd04_cgmprestador int not null,
            efd04_ano int not null,
            efd04_mes int not null
        );

        ALTER TABLE avaliacaogruporespostaretencaoservicostomados
        ADD CONSTRAINT avaliacaogruporespostaretencaoservicostomados_avaliacaogruporesposta_fk FOREIGN KEY (efd04_sequencial)
        REFERENCES avaliacaogruporesposta;

        ALTER TABLE avaliacaogruporespostaretencaoservicostomados
        ADD CONSTRAINT avaliacaogruporespostaretencaoservicostomados_cgmcontribuinte_fk FOREIGN KEY (efd04_cgmcontribuinte)
        REFERENCES cgm;

        ALTER TABLE avaliacaogruporespostaretencaoservicostomados
        ADD CONSTRAINT avaliacaogruporespostaretencaoservicostomados_cgmprestador_fk FOREIGN KEY (efd04_cgmprestador)
        REFERENCES cgm;

        CREATE  INDEX avaliacaogruporespostaretencaoservicostomados_avaliacaogruporesposta_in ON avaliacaogruporespostaretencaoservicostomados(efd04_sequencial);

        CREATE  INDEX avaliacaogruporespostaretencaoservicostomados_cgmcontribuinte_in ON avaliacaogruporespostaretencaoservicostomados(efd04_cgmcontribuinte);

        CREATE  INDEX avaliacaogruporespostaretencaoservicostomados_cgmprestador_in ON avaliacaogruporespostaretencaoservicostomados(efd04_cgmprestador);

SQL;
        $this->execute($sql);
    }

    public function formularioDown()
    {
        $sql = 
<<<SQL
        DROP INDEX avaliacaogruporespostaretencaoservicostomados_cgmprestador_in;
        DROP INDEX avaliacaogruporespostaretencaoservicostomados_cgmcontribuinte_in;
        DROP INDEX avaliacaogruporespostaretencaoservicostomados_avaliacaogruporesposta_in;
        DROP TABLE avaliacaogruporespostaretencaoservicostomados;
        DROP SEQUENCE avaliacaogruporespostaretencaoservicostomados_efd04_sequencial_seq;

        DELETE FROM db_sysprikey where codarq = 1010364;
        DELETE FROM db_sysarqcamp where codarq = 1010364;
        DELETE FROM db_syscampo where codcam = 1010242;
        DELETE FROM db_syscampo where codcam = 1010241;
        DELETE FROM db_syscampo where codcam = 1010240;
        DELETE FROM db_syscampo where codcam = 1010239;
        DELETE FROM db_syscampo where codcam = 1010238;
        DELETE FROM db_syscampo where codcam = 1010237;
        DELETE FROM db_sysarqmod where codarq = 1010364;
        DELETE FROM db_sysarquivo where codarq = 1010364;

        DELETE FROM efdreinfversaoformulario where efd03_avaliacao = 3000039;
        DELETE FROM esocialformulariotipo where rh209_sequencial = 26;

        create temp table x_avaliacaopergunta as
             select db103_sequencial
               from avaliacaopergunta
              where db103_avaliacaogrupopergunta in (select db102_sequencial from avaliacaogrupopergunta where db102_avaliacao = 3000039);
           create temp table x_avaliacaoperguntaopcao as
             select db104_sequencial
               from avaliacaoperguntaopcao
              where db104_avaliacaopergunta in (select db103_sequencial from x_avaliacaopergunta);           delete from avaliacaoperguntaopcao where db104_avaliacaopergunta in (select db103_sequencial from x_avaliacaopergunta);
           delete from avaliacaopergunta where db103_sequencial in (select db103_sequencial from x_avaliacaopergunta);
           delete from avaliacaogrupopergunta where db102_avaliacao = 3000039;
           delete from avaliacao where db101_sequencial = 3000039;           drop table x_avaliacaopergunta;
           drop table x_avaliacaoperguntaopcao;
SQL;
        $this->execute($sql);
    }

    private function upDicionario()
    {
        $sql =
<<<SQL
        INSERT INTO db_sysarquivo 
        VALUES (1010361, 'tiposerviconotafiscal', 'Referência utilizada para cadastrar as retenções e referenciar o envio para o EFD.', 'e18', '2018-12-20', 'Tipo Serviço de Nota Fiscal', 0, 'f', 'f', 'f', 'f' ),
            (1010362, 'retencaoreceitasadicionais', 'Retenção Receitas Adicionais referenciadas as Retenções Receitas', 'e19', '2018-12-20', 'Retenção Receitas Adicionais', 0, 'f', 'f', 'f', 'f' );

        INSERT INTO db_sysarqmod 
        VALUES (38,1010361),
            (38,1010362);

        INSERT INTO db_syscampo 
        VALUES (1010216,'e18_sequencial','int4','Sequencial do Tipo Serviço','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial'), 
            (1010217,'e18_referencia','varchar(10)','Referência para o EFD','', 'Referência para o EFD',10,'f','t','f',0,'text','Referência para o EFD'), 
            (1010218,'e18_descricao','varchar(100)','Descrição do Tipo de Serviço','', 'Descrição do Tipo de Serviço',100,'f','t','f',0,'text','Descrição do Tipo de Serviço'), 
            (1010219,'e19_sequencial','int4','Sequencial','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial'), 
            (1010220,'e19_retencaoreceitas','int4','Retenção Receitas','0', 'Retenção Receitas',10,'f','f','f',1,'text','Retenção Receitas'), 
            (1010221,'e19_tiposerviconotafiscal','int4','Tipo Serviço de Nota Fiscal','0', 'Tipo Serviço de Nota Fiscal',10,'f','f','f',1,'text','Tipo Serviço de Nota Fiscal'), 
            (1010222,'e19_valornaoretidoprincipal','float4','Valor da retenção principal que deixou de ser efetuada pelo contratante ou que foi depositada em juízo em decorrência de decisão judicial/administrativa','0', 'Valor Não Retido Principal',25,'f','f','f',4,'text','Valor Não Retido Principal'), 
            (1010223,'e19_valorservico15','float4','Valor dos Serviços prestados por segurados em condições especiais, cuja atividade permita concessão de aposentadoria especial após 15 anos de contribuição','0', 'Valor de Serviços 15 anos',25,'f','f','f',4,'text','Valor de Serviços 15 anos'), 
            (1010224,'e19_valorservico20','float4','Valor dos Serviços prestados por segurados em condições especiais, cuja atividade permita concessão de aposentadoria especial após 20 anos de contribuição.','0', 'Valor de Serviços 20 anos',25,'f','f','f',4,'text','Valor de Serviços 20 anos'), 
            (1010225,'e19_valorservico25','float4','Valor dos Serviços prestados por segurados em condições especiais, cuja atividade permita concessão de aposentadoria especial após 25 anos de contribuição.','0', 'Valor de Serviços 25 anos',25,'f','f','f',4,'text','Valor de Serviços 25 anos'), 
            (1010226,'e19_valoradicional','float4','Adicional apurado de retenção da nota fiscal, caso os serviços tenham sido prestados sob condições especiais que ensejem aposentadoria especial aos trabalhadores após 15, 20, ou 25 anos de contribuição.','0', 'Valor Adicional',10,'f','f','f',4,'text','Valor Adicional'),
            (1010227,'e19_valornaoretidoadicional','float4','Valor da retenção adicional que deixou de ser efetuada pelo contratante ou que foi depositada em juízo em decorrência de decisão judicial/administrativa','0', 'Valor Não Retido Adicional',25,'f','f','f',4,'text','Valor Não Retido Adicional');

        INSERT INTO db_sysarqcamp 
        VALUES (1010361,1010216,1,0), 
            (1010361,1010217,2,0), 
            (1010361,1010218,3,0), 
            (1010362,1010219,1,0), 
            (1010362,1010220,2,0), 
            (1010362,1010221,3,0), 
            (1010362,1010222,4,0), 
            (1010362,1010223,5,0), 
            (1010362,1010224,6,0), 
            (1010362,1010225,7,0), 
            (1010362,1010226,8,0), 
            (1010362,1010227,9,0);


        INSERT INTO db_sysprikey (codarq,codcam,sequen,camiden) 
        VALUES (1010361,1010216,1,1010218),
            (1010362,1010219,1,1010219);

        INSERT INTO db_sysforkey 
        VALUES (1010362,1010220,1,2116,0), 
            (1010362,1010221,1,1010361,0);


        INSERT INTO db_sysindices 
        VALUES (1008406,'retencaoreceitasadicionais_retencaoreceitas_in',1010362,'0'), 
            (1008407,'retencaoreceitasadicionais_tiposerviconotafiscal_in',1010362,'0');

        INSERT INTO db_syscadind 
        VALUES (1008406,1010220,1), 
            (1008407,1010221,1);

        INSERT INTO db_syssequencia 
        VALUES (1000803, 'tiposerviconotafiscal_e18_sequencial_seq', 1, 1, 9223372036854775807, 1, 1), 
            (1000804, 'retencaoreceitasadicionais_e19_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);        

        UPDATE db_sysarqcamp SET codsequencia = 1000803 WHERE codarq = 1010361 AND codcam = 1010216;
        UPDATE db_sysarqcamp SET codsequencia = 1000804 WHERE codarq = 1010362 AND codcam = 1010219;

        INSERT INTO db_syscampo VALUES (1010236,'pc60_indicativocprb','bool','Indicativo se o fornecedor contribui para a Contribuição Previdenciária sobre Receita Bruta(CPRB), para fins de uso no EFD Reinf.','f', 'Contribuinte do CPRB',1,'f','f','f',5,'text','Contribuinte do CPRB');
        INSERT INTO db_sysarqcamp VALUES (959,1010236,7,0);

        UPDATE db_syscampo SET nulo = true WHERE codcam IN (1010222,1010223,1010224,1010225,1010226,1010227);
SQL;
        $this->execute($sql);
    }

    private function upDDL()
    {
        $sql = 
<<<SQL

        CREATE SEQUENCE retencaoreceitasadicionais_e19_sequencial_seq
        INCREMENT 1
        MINVALUE 1
        MAXVALUE 9223372036854775807
        START 1
        CACHE 1;

        CREATE SEQUENCE tiposerviconotafiscal_e18_sequencial_seq
        INCREMENT 1
        MINVALUE 1
        MAXVALUE 9223372036854775807
        START 1
        CACHE 1;


        -- TABELAS E ESTRUTURA

        CREATE TABLE empenho.retencaoreceitasadicionais(
            e19_sequencial		        SERIAL,
            e19_retencaoreceitas        INTEGER NOT NULL,
            e19_tiposerviconotafiscal   INTEGER NOT NULL,
            e19_valornaoretidoprincipal NUMERIC(15, 2),
            e19_valorservico15          NUMERIC(15, 2),
            e19_valorservico20          NUMERIC(15, 2),
            e19_valorservico25          NUMERIC(15, 2),
            e19_valoradicional          NUMERIC(15, 2),
            e19_valornaoretidoadicional NUMERIC(15, 2),
            CONSTRAINT retencaoreceitasadicionais_sequ_pk PRIMARY KEY (e19_sequencial)
        );

        CREATE TABLE empenho.tiposerviconotafiscal(
            e18_sequencial		SERIAL,
            e18_referencia      VARCHAR(10) NOT NULL,
            e18_descricao       VARCHAR(100) NOT NULL,
            CONSTRAINT tiposerviconotafiscal_sequ_pk PRIMARY KEY (e18_sequencial)
        );

        ALTER TABLE retencaoreceitasadicionais
        ADD CONSTRAINT retencaoreceitasadicionais_retencaoreceitas_fk FOREIGN KEY (e19_retencaoreceitas)
        REFERENCES retencaoreceitas;

        ALTER TABLE retencaoreceitasadicionais
        ADD CONSTRAINT retencaoreceitasadicionais_tiposerviconotafiscal_fk FOREIGN KEY (e19_tiposerviconotafiscal)
        REFERENCES tiposerviconotafiscal;

        -- INDICES
        CREATE  INDEX retencaoreceitasadicionais_retencaoreceitas_in ON retencaoreceitasadicionais(e19_retencaoreceitas);
        CREATE  INDEX retencaoreceitasadicionais_tiposerviconotafiscal_in ON retencaoreceitasadicionais(e19_tiposerviconotafiscal);

        ALTER TABLE compras.pcforne
        ADD COLUMN pc60_indicativocprb BOOLEAN DEFAULT FALSE;
SQL;
        $this->execute($sql);
    }

    private function downDicionario()
    {
        $sql =
<<<SQL
        DELETE FROM db_sysforkey WHERE codarq in (1010361, 1010362) ;
        DELETE FROM db_sysprikey WHERE codarq in (1010361, 1010362);
        DELETE FROM db_sysindices WHERE codarq in (1010361, 1010362);
        DELETE FROM db_syscadind WHERE codind in (1008406, 1008407);
        DELETE FROM db_sysarqmod WHERE codarq in (1010361, 1010362);
        DELETE FROM db_sysarqcamp WHERE codarq in (1010361, 1010362);
        DELETE FROM db_syscampo WHERE codcam in (1010216, 1010217, 1010218, 1010219, 1010220, 1010221, 1010222, 1010223, 1010224, 1010225, 1010226, 1010227);
        DELETE FROM db_syssequencia WHERE codsequencia in (1000803, 1000804);
        DELETE FROM db_sysarquivo WHERE codarq in (1010361, 1010362);
        
        DELETE FROM db_sysarqcamp WHERE codarq = 959 AND codcam = 1010236;
        DELETE FROM db_syscampo WHERE codcam = 1010236;
SQL;
        $this->execute($sql);
    }

    private function downDDL()
    {
        $sql =
<<<SQL
        DROP TABLE IF EXISTS prestadorprocessoefd CASCADE;
        DROP TABLE IF EXISTS retencaoreceitasadicionais CASCADE;
        DROP TABLE IF EXISTS tiposerviconotafiscal CASCADE;
        
        DROP SEQUENCE IF EXISTS prestadorprocessoefd_e287_sequencial_seq;
        DROP SEQUENCE IF EXISTS retencaoreceitasadicionais_e19_sequencial_seq;
        DROP SEQUENCE IF EXISTS tiposerviconotafiscal_e18_sequencial_seq;
        
        ALTER TABLE compras.pcforne
        DROP COLUMN IF EXISTS pc60_indicativocprb;
SQL;
        $this->execute($sql);
    }

    private function populationDDL()
    {
        $sql = 
<<<SQL
        INSERT INTO tiposerviconotafiscal (e18_referencia, e18_descricao)
        VALUES ('100000001', 'Limpeza, conservação ou zeladoria'),
            ('100000002', 'Vigilância ou segurança'),
            ('100000003', 'Construção civil'),
            ('100000004', 'Serviços de natureza rural'),
            ('100000005', 'Digitação'),
            ('100000006', 'Preparação de dados para processamento'),
            ('100000007', 'Acabamento'),
            ('100000008', 'Embalagem'),
            ('100000009', 'Acondicionamento'),
            ('100000010', 'Cobrança'),
            ('100000011', 'Coleta ou reciclagem de lixo ou de resíduos'),
            ('100000012', 'Copa'),
            ('100000013', 'Hotelaria'),
            ('100000014', 'Corte ou ligação de serviços públicos'),
            ('100000015', 'Distribuição'),
            ('100000016', 'Treinamento e ensino'),
            ('100000017', 'Entrega de contas e de documentos'),
            ('100000018', 'Ligação de medidores'),
            ('100000019', 'Leitura de medidores'),
            ('100000020', 'Manutenção de instalações, de máquinas ou de equipamentos'),
            ('100000021', 'Montagem'),
            ('100000022', 'Operação de máquinas, de equipamentos e de veículos'),
            ('100000023', 'Operação de pedágio ou de terminal de transporte'),
            ('100000024', 'Operação de transporte de passageiros'),
            ('100000025', 'Portaria, recepção ou ascensorista'),
            ('100000026', 'Recepção, triagem ou movimentação de materiais'),
            ('100000027', 'Promoção de vendas ou de eventos'),
            ('100000028', 'Secretaria e expediente'),
            ('100000029', 'Saúde'),
            ('100000030', 'Telefonia ou telemarketing'),
            ('100000031', 'Trabalho temporário na forma da Lei nº 6.019, de janeiro de 197');
SQL;
        $this->execute($sql);
    }

    private function itemMenuUp()
    {
        $sql = 
<<<SQL
        insert into db_itensmenu( id_item ,descricao ,help ,funcao ,itemativo ,manutencao ,desctec ,libcliente ) values ( 228088 ,'Retenção Contribuição Previdenciária - Serviços Tomados' ,'R2010' ,'efd04_r2010_servicos_tomados001.php' ,'1' ,'1' ,'Formulário de envio do arquivo R2010 do EFD-Reinf' ,'true' );
        delete from db_menu where id_item_filho = 228088 AND modulo = 228077;
        insert into db_menu( id_item ,id_item_filho ,menusequencia ,modulo ) values ( 228079 ,228088 ,5 ,228077 );        
SQL;
        $this->execute($sql);
    }

    private function itemMenuDown()
    {
        $sql = 
<<<SQL
        delete from db_menu where id_item_filho = 228088 AND modulo = 228077;
        delete from db_itensmenu where id_item = 228088;
SQL;
        $this->execute($sql);
    }
}
