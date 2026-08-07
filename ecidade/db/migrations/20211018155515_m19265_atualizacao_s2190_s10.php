<?php

use Classes\PostgresMigration;

class M19265AtualizacaoS2190S10 extends PostgresMigration
{
    public function up()
    {
        $this->criaPerguntas();
        $this->atualizaDescricaoFormularioUp();

        $this->criarCampoNovoDicionario();
        $this->criarCampoNovoEstrutura();
        $this->acertarMatriculasServidores();
        $this->upPerguntaSomenteLeitura();
    }
    public function down()
    {
        $this->excluiPerguntas();
        $this->atualizaDescricaoFormularioDown();

        $this->removerCampoNovoDicionario();
        $this->removerCampoNovoEstrutura();
        $this->downPerguntaSomenteLeitura();
    }
    
    private function criaPerguntas()
    {
        $sql = <<<SQL
        insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ,db103_somenteleitura ) values ( 4000299 ,2 ,3000240 ,'Matricula do trabalhador' ,'matricula_trabalhador' ,'true' ,'true' ,4 ,1 ,'' ,0 ,'false' ,'' ,'matricula' ,'true' );
        insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001382 ,4000299 ,'' ,'616dd0ec88300' ,'true' ,0 ,'' ,'matricula' );
        insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ,db103_somenteleitura ) values ( 4000300 ,2 ,3000240 ,'Preencher com o código da categoria do trabalhador.' ,'codCateg' ,'true' ,'true' ,5 ,1 ,'' ,0 ,'false' ,'' ,'codCateg' ,'false' );
        insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001383 ,4000300 ,'' ,'616dd0ec8b12a' ,'true' ,0 ,'' ,'codCateg' );
        insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ,db103_somenteleitura ) values ( 4000301 ,1 ,3000240 ,'Natureza da atividade' ,'natAtividade' ,'true' ,'true' ,6 ,6 ,'' ,0 ,'false' ,'' ,'natAtividade' ,'false' );
        insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001384 ,4000301 ,'1 - Trabalho urbano' ,'trabalho-urbano-1' ,'false' ,0 ,'1' ,'natAtividade1' );
        insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001385 ,4000301 ,'2 - Trabalho rural' ,'trabalho-rural-2' ,'false' ,0 ,'2' ,'natAtividade2' );
        insert into habitacao.avaliacaogrupopergunta( db102_sequencial ,db102_avaliacao ,db102_descricao ,db102_identificador ,db102_identificadorcampo ,db102_ordem ) values ( 4000233 ,3000021 ,'Registro eletrônico de empregados - CTPS Digital.' ,'infoRegCTPS' ,'infoRegCTPS' ,2 );
        insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ,db103_somenteleitura ) values ( 4000302 ,2 ,4000233 ,'Classificação Brasileira de Ocupações - CBO' ,'codCBO' ,'true' ,'true' ,1 ,1 ,'' ,0 ,'false' ,'' ,'CBOCargo' ,'false' );
        insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001386 ,4000302 ,'' ,'616dd0ec91754' ,'true' ,0 ,'' ,'codCBO' );
        insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ,db103_somenteleitura ) values ( 4000303 ,2 ,4000233 ,'Salário base do trabalhador' ,'vrSalFx' ,'true' ,'true' ,2 ,8 ,'' ,0 ,'false' ,'' ,'vrSalFx' ,'false' );
        insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001387 ,4000303 ,'' ,'616dd0ec93a28' ,'false' ,0 ,'' ,'vrSalFx' );
        insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ,db103_somenteleitura ) values ( 4000304 ,1 ,4000233 ,'Unidade de pagamento da parte fixa da remunerário' ,'undSalFixo' ,'true' ,'true' ,3 ,6 ,'' ,0 ,'false' ,'' ,'undSalFixo' ,'false' );
        insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001388 ,4000304 ,'1 - Por Hora' ,'unidade-salario-hora-1' ,'false' ,0 ,'1' ,'undSalFixo1' );
        insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001389 ,4000304 ,'2 - Por Dia' ,'unidade-salario-dia-2' ,'false' ,0 ,'2' ,'undSalFixo2' );
        insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001390 ,4000304 ,'3 - Por Semana' ,'unidade-salario-semana-3' ,'false' ,0 ,'3' ,'undSalFixo3' );
        insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001391 ,4000304 ,'4 - Por Quinzena' ,'unidade-salario-quinzena-4' ,'false' ,0 ,'4' ,'undSalFixo4' );
        insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001392 ,4000304 ,'5 - Por mes' ,'unidade-salario-mes-5' ,'false' ,0 ,'5' ,'undSalFixo5' );
        insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001393 ,4000304 ,'6 - Por Tarefa' ,'unidade-salario-tarefa-6' ,'false' ,0 ,'6' ,'undSalFixo6' );
        insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001394 ,4000304 ,'7 - Não Aplicável' ,'unidade-salario-nao-aplicavel-' ,'false' ,0 ,'7' ,'undSalFixo7' );
        insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ,db103_somenteleitura ) values ( 4000305 ,1 ,4000233 ,'Tipo de contrato de trabalho' ,'tpContr' ,'true' ,'true' ,4 ,6 ,'' ,0 ,'false' ,'' ,'tpContr' ,'false' );
        insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001395 ,4000305 ,'1 - Prazo Inderteminado' ,'tipo-contrato-1' ,'false' ,0 ,'1' ,'tpContr1' );
        insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001396 ,4000305 ,'2 - Prazo determinado, definido em dias' ,'tipo-contrato-2' ,'false' ,0 ,'2' ,'tpContr2' );
        insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001397 ,4000305 ,'3 - Prazo determinado, vinculado à ocorrência de um fato' ,'tipo-contrato-3' ,'false' ,0 ,'3' ,'tpContr3' );
        insert into habitacao.avaliacaopergunta( db103_sequencial ,db103_avaliacaotiporesposta ,db103_avaliacaogrupopergunta ,db103_descricao ,db103_identificador ,db103_obrigatoria ,db103_ativo ,db103_ordem ,db103_tipo ,db103_mascara ,db103_dblayoutcampo ,db103_perguntaidentificadora ,db103_camposql ,db103_identificadorcampo ,db103_somenteleitura ) values ( 4000306 ,2 ,4000233 ,'Data do término do contrato por prazo determinado' ,'data-termino-contrato' ,'false' ,'true' ,5 ,5 ,'' ,0 ,'false' ,'' ,'dtTerm' ,'false' );
        insert into habitacao.avaliacaoperguntaopcao( db104_sequencial ,db104_avaliacaopergunta ,db104_descricao ,db104_identificador ,db104_aceitatexto ,db104_peso ,db104_valorresposta ,db104_identificadorcampo ) values ( 4001398 ,4000306 ,'' ,'data-termino-contrato-1' ,'true' ,0 ,'' ,'dtTerm' );
SQL;
        $this->execute($sql);

    }
    private function excluiPerguntas()
    {

        $sql = <<<SQL
        delete from habitacao.avaliacaoperguntaopcao where db104_sequencial = 4001382;
        delete from habitacao.avaliacaoperguntaopcao where db104_sequencial = 4001383; 
        delete from habitacao.avaliacaoperguntaopcao where db104_sequencial = 4001384; 
        delete from habitacao.avaliacaoperguntaopcao where db104_sequencial = 4001385; 
        delete from habitacao.avaliacaoperguntaopcao where db104_sequencial = 4001386;        
        delete from habitacao.avaliacaoperguntaopcao where db104_sequencial = 4001387; 
        delete from habitacao.avaliacaoperguntaopcao where db104_sequencial = 4001388; 
        delete from habitacao.avaliacaoperguntaopcao where db104_sequencial = 4001389; 
        delete from habitacao.avaliacaoperguntaopcao where db104_sequencial = 4001390; 
        delete from habitacao.avaliacaoperguntaopcao where db104_sequencial = 4001391; 
        delete from habitacao.avaliacaoperguntaopcao where db104_sequencial = 4001392; 
        delete from habitacao.avaliacaoperguntaopcao where db104_sequencial = 4001393; 
        delete from habitacao.avaliacaoperguntaopcao where db104_sequencial = 4001394; 
        delete from habitacao.avaliacaoperguntaopcao where db104_sequencial = 4001395; 
        delete from habitacao.avaliacaoperguntaopcao where db104_sequencial = 4001396; 
        delete from habitacao.avaliacaoperguntaopcao where db104_sequencial = 4001397; 
        delete from habitacao.avaliacaoperguntaopcao where db104_sequencial = 4001398; 

        delete from habitacao.avaliacaopergunta      where db103_sequencial = 4000299;
        delete from habitacao.avaliacaopergunta      where db103_sequencial = 4000300;
        delete from habitacao.avaliacaopergunta      where db103_sequencial = 4000301; 
        delete from habitacao.avaliacaopergunta      where db103_sequencial = 4000302; 
        delete from habitacao.avaliacaopergunta      where db103_sequencial = 4000303; 
        delete from habitacao.avaliacaopergunta      where db103_sequencial = 4000304; 
        delete from habitacao.avaliacaopergunta      where db103_sequencial = 4000305; 
        delete from habitacao.avaliacaopergunta      where db103_sequencial = 4000306; 

        delete from habitacao.avaliacaogrupopergunta where db102_sequencial = 4000233; 
SQL;
        $this->execute($sql);

    }

    private function atualizaDescricaoFormularioUp()
    {
        $sql = <<<SQL
        update habitacao.avaliacao set db101_descricao = 'S-2190 - Registro Preliminar de Trabalhador' where db101_sequencial = 3000021;
SQL;
        $this->execute($sql);
    }

    private function atualizaDescricaoFormularioDown()
    {
        $sql = <<<SQL
        update habitacao.avaliacao set db101_descricao = 'S2260 - Convocação para Trabalho Intermitente' where db101_sequencial = 3000021;
SQL;
        $this->execute($sql);
    }

    private function criarCampoNovoDicionario()
    {
        $sql = <<<SQL
        insert into configuracoes.db_syscampo values(1013464,'eso18_regist','int4','matricula do servidor para o preenchimento','0', 'Matricula',10,'f','f','f',1,'text','Matricula');
        insert into configuracoes.db_sysarqcamp values(1010314,1013464,5,0);
        insert into configuracoes.db_sysforkey values(1010314,1013464,1,1153,0);
SQL;
        $this->execute($sql);
    }

    private function removerCampoNovoDicionario()
    {
        $sql = <<<SQL
        delete from configuracoes.db_sysforkey where codarq = 1010314 and codcam = 1013464;
        delete from configuracoes.db_sysarqcamp where codarq = 1010314 and codcam = 1013464;
        delete from configuracoes.db_syscampo where codcam = 1013464;
SQL;
        $this->execute($sql);
    }

    private function criarCampoNovoEstrutura()
    {
        $sql = <<<SQL
        ALTER TABLE esocial.avaliacaogruporespostaadmissaopreliminar ADD COLUMN eso18_regist integer;
        
        ALTER TABLE esocial.avaliacaogruporespostaadmissaopreliminar
        ADD CONSTRAINT avaliacaogruporespostaadmissaopreliminar_regist_fk FOREIGN KEY (eso18_regist)
        REFERENCES rhpessoal;
SQL;
        $this->execute($sql);
    }

    private function removerCampoNovoEstrutura()
    {
        $sql = <<<SQL
        ALTER TABLE esocial.avaliacaogruporespostaadmissaopreliminar
        DROP CONSTRAINT IF EXISTS avaliacaogruporespostaadmissaopreliminar_regist_fk;

        ALTER TABLE esocial.avaliacaogruporespostaadmissaopreliminar DROP COLUMN IF EXISTS eso18_regist;
SQL;
        $this->execute($sql);
    }

    private function acertarMatriculasServidores()
    {
        $sql = <<<SQL
            UPDATE esocial.avaliacaogruporespostaadmissaopreliminar
            SET eso18_regist=subquery.rh01_regist
            FROM (
                    SELECT rh.rh01_regist,
                        rh.rh01_numcgm,
                        rh.rh01_instit,
                        c.z01_cgccpf,
                        cf.codigo ,
                        cf.numcgm 
                    FROM pessoal.rhpessoal rh
                    INNER JOIN protocolo.cgm c ON c.z01_numcgm = rh.rh01_numcgm
                    INNER JOIN configuracoes.db_config cf ON cf.codigo = rh01_instit
                ) AS subquery
            WHERE esocial.avaliacaogruporespostaadmissaopreliminar.eso18_cpf =subquery.z01_cgccpf 
            AND esocial.avaliacaogruporespostaadmissaopreliminar.eso18_regist IS NULL
            AND EXISTS (SELECT 1 FROM configuracoes.db_config x WHERE x.numcgm = esocial.avaliacaogruporespostaadmissaopreliminar.eso18_cgm)
            AND subquery.z01_cgccpf = eso18_cpf
            AND subquery.numcgm = eso18_cgm;
SQL;
        $this->execute($sql);
    }

    private function upPerguntaSomenteLeitura()
    {
        $sql = <<<SQL
        UPDATE habitacao.avaliacaopergunta SET db103_somenteleitura=true WHERE db103_sequencial=3001020;
        UPDATE habitacao.avaliacaopergunta SET db103_somenteleitura=true WHERE db103_sequencial=3001021;
        UPDATE habitacao.avaliacaopergunta SET db103_somenteleitura=true WHERE db103_sequencial=3001022;
SQL;

    }

    private function downPerguntaSomenteLeitura()
    {
        $sql = <<<SQL
        UPDATE habitacao.avaliacaopergunta SET db103_somenteleitura=false WHERE db103_sequencial=3001020;
        UPDATE habitacao.avaliacaopergunta SET db103_somenteleitura=false WHERE db103_sequencial=3001021;
        UPDATE habitacao.avaliacaopergunta SET db103_somenteleitura=false WHERE db103_sequencial=3001022;
SQL;

    }
}