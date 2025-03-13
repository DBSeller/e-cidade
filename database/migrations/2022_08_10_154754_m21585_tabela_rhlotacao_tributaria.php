<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M21585TabelaRhlotacaoTributaria extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->upDicionario();
        $this->upEstrutura();
        $this->poupularLotacaoTributaria();
        $this->upAtualizaReferenciaLotacaoTributaria();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->downDicionario();
        $this->downEstrutura();
        $this->downAtualizaReferenciaLotacaoTributaria();
    }

    private function upDicionario()
    {
        $sql = <<<SQL
    -- Tabela rhlotacaotributaria
    insert into configuracoes.db_sysarquivo values (1010981, 'rhlotacaotributaria', 'Condição diferenciada de tributação que acontece através da informação prestada ao governo a respeito de quem receberá o FPAS (Fundo da Previdência e Assistência Social) pago por determinada empresa', 'rh268', '2022-08-10', 'Lotação Tributária', 0, 'f', 'f', 'f', 'f' );
    insert into configuracoes.db_sysarqmod values (29,1010981);
    insert into configuracoes.db_syscampo values(1014439,'rh268_sequencial','int8','Código sequencial único','0', 'Código',10,'f','f','t',1,'text','Código');
    insert into configuracoes.db_syscampo values(1014440,'rh268_numcgm','int8','Número único de Código Geral de Matrícula da Instituição','0', 'CGM',10,'f','f','f',1,'text','CGM');
    insert into configuracoes.db_syscampo values(1014441,'rh268_codigolotacao','varchar(50)','Código atribuído pelo empregador para a lotação tributária.','', 'Código Lotação',50,'f','t','f',0,'text','Código Lotação');
    insert into configuracoes.db_sysarqcamp values(1010981,1014439,1,0);
    insert into configuracoes.db_sysarqcamp values(1010981,1014440,2,0);
    insert into configuracoes.db_sysarqcamp values(1010981,1014441,3,0);
    insert into configuracoes.db_sysprikey (codarq,codcam,sequen,camiden) values(1010981,1014439,1,1014439);
    insert into configuracoes.db_sysforkey values(1010981,1014440,1,42,0);
    insert into configuracoes.db_syssequencia values(1001087, 'rhlotacaotributaria_rh268_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);
    update configuracoes.db_sysarqcamp set codsequencia = 1001087 where codarq = 1010981 and codcam = 1014439;

    -- Inclusão do campo na tabela rhlocaltrab
    insert into configuracoes.db_syscampo values(1014450,'rh55_lotacaotributaria','varchar(30)','Código atribuído pelo empregador para a lotação tributária.','', 'Lotação Tributária',30,'t','t','f',0,'text','Lotação Tributária');
    insert into configuracoes.db_sysarqcamp values(1542,1014450,12,0);

    -- Ajuste no dicionário dos clientes, pois não estava preenchido
    UPDATE configuracoes.db_sysarquivo
    SET nomearq = 'rhlocaltrab',
        descricao = 'Cadastro do Local de Trabalho',
        sigla = 'rh55 ',
        dataincl = '2006-06-27',
        rotulo = 'Local de Trabalho'
    WHERE codarq = 1542;
SQL;
        DB::connection()->getPdo()->exec($sql);       
    }

    private function upEstrutura()
    {
        $sql = <<<SQL
        -- Criando  sequences
        CREATE SEQUENCE recursoshumanos.rhlotacaotributaria_rh268_sequencial_seq
        INCREMENT 1
        MINVALUE 1
        MAXVALUE 9223372036854775807
        START 1
        CACHE 1;
        -- TABELAS E ESTRUTURA
        -- Módulo: recursoshumanos
        CREATE TABLE recursoshumanos.rhlotacaotributaria(
        rh268_sequencial		int8 NOT NULL default nextval('rhlotacaotributaria_rh268_sequencial_seq'),
        rh268_numcgm		int8 NOT NULL default 0,
        rh268_codigolotacao		varchar(50)  default '',
        CONSTRAINT rhlotacaotributaria_sequ_pk PRIMARY KEY (rh268_sequencial));

        ALTER TABLE recursoshumanos.rhlotacaotributaria
        ADD CONSTRAINT rhlotacaotributaria_numcgm_fk FOREIGN KEY (rh268_numcgm)
        REFERENCES cgm;
        
        -- Criação da coluna
        ALTER TABLE pessoal.rhlocaltrab ADD rh55_lotacaotributaria varchar(40) NULL;
        
SQL;
        DB::connection()->getPdo()->exec($sql);       
    }

    private function downDicionario()
    {
        $sql = <<<SQL
    -- Tabela rhlotacaotributaria
    delete from configuracoes.db_syssequencia where codsequencia = 1001087;
    delete from configuracoes.db_sysforkey where codarq = 1010981;
    delete from configuracoes.db_sysprikey where codarq = 1010981;
    delete from configuracoes.db_sysarqcamp where codarq = 1010981;
    delete from configuracoes.db_syscampo where codcam in (1014439, 1014440, 1014441);
    delete from configuracoes.db_sysarqmod where codmod = 29 and codarq = 1010981;
    delete from configuracoes.db_sysarquivo where codarq = 1010981;

    -- Inclusão do campo na tabela rhlocaltrab
    delete from configuracoes.db_sysarqcamp where codarq = 1542 and codcam = 1014450;
    delete from configuracoes.db_syscampo where codcam = 1014450;
SQL;
        DB::connection()->getPdo()->exec($sql);       
    }

    private function downEstrutura()
    {
        $sql = <<<SQL
            --DROP TABLE:
            DROP TABLE IF EXISTS recursoshumanos.rhlotacaotributaria;
            --Drop sequences
            DROP SEQUENCE IF EXISTS recursoshumanos.rhlotacaotributaria_rh268_sequencial_seq;  

            --Exclusao da coluna
            ALTER TABLE pessoal.rhlocaltrab DROP rh55_lotacaotributaria      
SQL;
        DB::connection()->getPdo()->exec($sql);       
    }

    private function poupularLotacaoTributaria()
    {
        $sql = <<<SQL

        INSERT INTO recursoshumanos.rhlotacaotributaria (rh268_numcgm, rh268_codigolotacao)
        select
            numcgm::int8  as rh268_numcgm,
            case
                when db106_resposta = ''
                and db103_avaliacaotiporesposta <> 2 then case
                    when db103_avaliacaotiporesposta = 1
                    and db104_sequencial in (
                    select
                        db104_sequencial
                    from
                        avaliacaopergunta
                    inner join avaliacaoperguntaopcao on
                        db104_avaliacaopergunta = db103_sequencial
                    inner join avaliacaoresposta on
                        db106_avaliacaoperguntaopcao = db104_sequencial
                    where
                        db103_sequencial = 3000860
                    order by
                        db106_sequencial desc
                    limit 1) then '1'
                    else '1'
                end
                else db106_resposta
            end as rh268_codigolotacao
        from
            esocial.avaliacaogruporespostalotacao
        inner join habitacao.avaliacaogruporesposta on
            db107_sequencial = eso04_avaliacaogruporesposta
        inner join habitacao.avaliacaogrupoperguntaresposta on
            db108_avaliacaogruporesposta = db107_sequencial
        inner join habitacao.avaliacaoresposta on
            db106_sequencial = db108_avaliacaoresposta
        inner join habitacao.avaliacaoperguntaopcao on
            db104_sequencial = db106_avaliacaoperguntaopcao
        inner join habitacao.avaliacaopergunta on
            db103_sequencial = db104_avaliacaopergunta and db103_sequencial = 3000860
        inner join configuracoes.db_config on numcgm = eso04_cgm
        where eso04_avaliacaogruporesposta in (
            select
                max(eso04_avaliacaogruporesposta) over (partition by eso04_cgm)
            from
                esocial.avaliacaogruporespostalotacao
            inner join habitacao.avaliacaogruporesposta on
                db107_sequencial = eso04_avaliacaogruporesposta
            inner join habitacao.avaliacaogrupoperguntaresposta on
                db108_avaliacaogruporesposta = db107_sequencial
            inner join habitacao.avaliacaoresposta on
                db106_sequencial = db108_avaliacaoresposta
            inner join habitacao.avaliacaoperguntaopcao on
                db104_sequencial = db106_avaliacaoperguntaopcao
            inner join habitacao.avaliacaopergunta on
                db103_sequencial = db104_avaliacaopergunta and db103_sequencial = 3000860
            inner join configuracoes.db_config on numcgm = eso04_cgm
            );

            -- Preenchimento da coluna rh55_lotacaotributaria conforme tabela rhlotacaotributaria
            UPDATE pessoal.rhlocaltrab 
            SET rh55_lotacaotributaria = rh268_codigolotacao
            FROM recursoshumanos.rhlotacaotributaria
            inner join configuracoes.db_config on numcgm = rh268_numcgm
            WHERE rhlocaltrab.rh55_instit = db_config.codigo;
SQL;
        DB::connection()->getPdo()->exec($sql);       
    }
    
    private function upAtualizaReferenciaLotacaoTributaria()
    {
        $sql = <<<SQL
            UPDATE esocial.esocialenvio
            SET rh213_responsavelpreenchimento=rh213_responsavelpreenchimento||'-'||((convert_to(rh213_dados::text, 'UTF8')::text)::jsonb#>>'{ideLotacao,codLotacao}')::text
            WHERE rh213_evento='1020';
SQL;
        DB::connection()->getPdo()->exec($sql);       
}


private function downAtualizaReferenciaLotacaoTributaria()
{
    $sql = <<<SQL
        UPDATE esocial.esocialenvio
        SET rh213_responsavelpreenchimento=trim(rh213_empregador::text)
        WHERE rh213_evento='1020';
SQL;
        DB::connection()->getPdo()->exec($sql);       
    }
}
