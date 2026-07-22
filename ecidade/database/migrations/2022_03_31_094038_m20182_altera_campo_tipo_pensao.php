<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20182AlteraCampoTipoPensao extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sql = <<<SQL
        alter table pessoal.rhpessoalmov disable trigger tg_pessoalmov_alt;
        ALTER TABLE pessoal.rhpessoalmov
            drop CONSTRAINT rhpessoalmov_rhtipoapos_fk;

        alter table pessoal.rhtipoapos ALTER COLUMN rh88_sequencial TYPE varchar(4);
        alter table pessoal.rhtipoapos ALTER COLUMN rh88_descricao TYPE varchar(200);
        alter table pessoal.rhpessoalmov ADD COLUMN rh02_descinstrumento varchar(255) default NULL;
        alter table pessoal.rhpessoalmov ADD COLUMN rh02_sitpagbeneficio BOOL DEFAULT 'f';
        alter table pessoal.rhpessoalmov ALTER COLUMN rh02_rhtipoapos TYPE varchar(4);

        delete from pessoal.rhtipoapos;
        insert into pessoal.rhtipoapos values('0', 'Nenhum');
        insert into pessoal.rhtipoapos values('0101', 'Aposentadoria por idade e tempo de contribuição - Proventos com integralidade, revisão pela paridade');
        insert into pessoal.rhtipoapos values('0102', 'Aposentadoria por idade e tempo de contribuição - Proventos pela média, reajuste manter valor real');
        insert into pessoal.rhtipoapos values('0103', 'Aposentadoria por idade - Proventos proporcionais calculado sobre integralidade, revisão pela paridade');
        insert into pessoal.rhtipoapos values('0104', 'Aposentadoria por idade - Proventos proporcionais calculado sobre a média, reajuste manter valor real');
        insert into pessoal.rhtipoapos values('0105', 'Aposentadoria compulsória - Proventos proporcionais calculado sobre integralidade, revisão pela paridade');
        insert into pessoal.rhtipoapos values('0106', 'Aposentadoria compulsória - Proventos proporcionais calculado sobre a média, reajuste manter valor real');
        insert into pessoal.rhtipoapos values('0107', 'Aposentadoria de professor - Proventos com integralidade, revisão pela paridade');
        insert into pessoal.rhtipoapos values('0108', 'Aposentadoria de professor - Proventos pela média, reajuste manter valor real');
        insert into pessoal.rhtipoapos values('0109', 'Aposentadoria de servidor vinculado a RPC - Proventos limitados ao teto do RGPS');
        insert into pessoal.rhtipoapos values('0201', 'Aposentadoria especial - Risco');
        insert into pessoal.rhtipoapos values('0202', 'Aposentadoria especial - Exposição a agentes nocivos');
        insert into pessoal.rhtipoapos values('0203', 'Aposentadoria da pessoa com deficiência');
        insert into pessoal.rhtipoapos values('0204', 'Aposentadoria especial do policial civil');
        insert into pessoal.rhtipoapos values('0205', 'Aposentadoria especial - Risco - Servidor vinculado a RPC - Proventos limitados ao teto do RGPS');
        insert into pessoal.rhtipoapos values('0206', 'Aposentadoria especial - Exposição a agentes nocivos - Servidor vinculado a RPC - Proventos limitados ao teto do RGPS');
        insert into pessoal.rhtipoapos values('0207', 'Aposentadoria da pessoa com deficiência - Servidor vinculado a RPC - Proventos limitados ao teto do RGPS');
        insert into pessoal.rhtipoapos values('0208', 'Aposentadoria especial de policial - Servidor vinculado a RPC - Proventos limitados ao teto do RGPS');
        insert into pessoal.rhtipoapos values('0301', 'Aposentadoria por invalidez - Proventos com integralidade, revisão pela paridade');
        insert into pessoal.rhtipoapos values('0302', 'Aposentadoria por invalidez - Proventos pela média, reajuste manter valor real');
        insert into pessoal.rhtipoapos values('0303', 'Aposentadoria por invalidez - Proventos proporcionais calculado sobre integralidade, revisão pela paridade');
        insert into pessoal.rhtipoapos values('0304', 'Aposentadoria por invalidez - Proventos proporcionais calculado sobre a média, reajuste manter valor real');
        insert into pessoal.rhtipoapos values('0305', 'Aposentadoria por invalidez - Servidor vinculado a RPC - Proventos limitados ao teto do RGPS');
        insert into pessoal.rhtipoapos values('0401', 'Reforma por invalidez');
        insert into pessoal.rhtipoapos values('0402', 'Reforma');
        insert into pessoal.rhtipoapos values('0403', 'Reforma compulsória proporcional');
        insert into pessoal.rhtipoapos values('0404', 'Reforma compulsória integral');
        insert into pessoal.rhtipoapos values('0405', 'Reforma por incapacidade definitiva');
        insert into pessoal.rhtipoapos values('0501', 'Reserva remunerada compulsória integral');
        insert into pessoal.rhtipoapos values('0502', 'Reserva remunerada integral');
        insert into pessoal.rhtipoapos values('0503', 'Reserva remunerada proporcional');
        insert into pessoal.rhtipoapos values('0504', 'Reserva remunerada compulsória proporcional');
        insert into pessoal.rhtipoapos values('0601', 'Pensão por morte (art. 40, § 7º, da CF/1988)');
        insert into pessoal.rhtipoapos values('0602', 'Pensão por morte com paridade, decorrente do art. 6º-A da EC 41/2003');
        insert into pessoal.rhtipoapos values('0603', 'Pensão por morte com paridade, decorrente do art. 3º da EC 47/2005');
        insert into pessoal.rhtipoapos values('0604', 'Pensão por morte militar');
        insert into pessoal.rhtipoapos values('0701', 'Complementação de aposentadoria do RGPS');
        insert into pessoal.rhtipoapos values('0702', 'Complementação de pensão por morte do RGPS');
        insert into pessoal.rhtipoapos values('0901', 'Benefício especial proporcional - Servidor pertencente a RPPS que opta pelo RPC da União');
        insert into pessoal.rhtipoapos values('0902', 'Benefício especial proporcional - Servidor pertencente a RPPS que opta pelo RPC - Demais entes da Federação, de acordo com as disposições das leis específicas');
        insert into pessoal.rhtipoapos values('0909', 'Outros benefícios especiais com vínculo previdenciário');
        insert into pessoal.rhtipoapos values('1001', 'Pensão especial sem vínculo previdenciário');
        insert into pessoal.rhtipoapos values('1009', 'Outros benefícios sem vínculo previdenciário');
        insert into pessoal.rhtipoapos values('1101', 'Aposentadoria voluntária com proventos proporcionais ao tempo de mandato - Leis próprias');
        insert into pessoal.rhtipoapos values('1102', 'Aposentadoria voluntária com proventos integrais ao tempo de mandato - Leis próprias');
        insert into pessoal.rhtipoapos values('1103', 'Aposentadoria por invalidez permanente - Proventos integrais - Leis próprias');
        insert into pessoal.rhtipoapos values('1104', 'Aposentadoria por invalidez permanente - Proventos proporcionais ao tempo de mandato - Leis próprias');
        insert into pessoal.rhtipoapos values('1105', 'Pensão por morte de parlamentar - Lei específica');
        insert into pessoal.rhtipoapos values('1106', 'Pensão por morte de parlamentar - Planos anteriores à EC 20/1998');


        -- Acerto em Base
        WITH dados_atualizar AS (
          select rh01_instit, rh01_regist, rh01_reajusteparidade from rhpessoal inner join rhpessoalmov on rh02_regist = rh01_regist and rh01_instit = rh02_instit where rh02_rhtipoapos != '0'
        )
        UPDATE
          rhpessoalmov
        SET
            rh02_rhtipoapos = (CASE WHEN rh02_rhtipoapos = '1' and rh01_reajusteparidade = 0 then '0601'
                WHEN rh02_rhtipoapos = '1' and rh01_reajusteparidade = 1 then '0601'
                WHEN rh02_rhtipoapos = '1' and rh01_reajusteparidade = 2 then '0603'
                WHEN rh02_rhtipoapos = '2' and rh01_reajusteparidade = 0 then '0102'
                WHEN rh02_rhtipoapos = '2' and rh01_reajusteparidade = 1 then '0102'
                WHEN rh02_rhtipoapos = '2' and rh01_reajusteparidade = 2 then '0101'
                WHEN rh02_rhtipoapos = '3' and rh01_reajusteparidade = 0 then '0102'
                WHEN rh02_rhtipoapos = '3' and rh01_reajusteparidade = 1 then '0102'
                WHEN rh02_rhtipoapos = '3' and rh01_reajusteparidade = 2 then '0103'
                WHEN rh02_rhtipoapos = '4' and rh01_reajusteparidade = 0 then '0302'
                WHEN rh02_rhtipoapos = '4' and rh01_reajusteparidade = 1 then '0302'
                WHEN rh02_rhtipoapos = '4' and rh01_reajusteparidade = 2 then '0301'
                WHEN rh02_rhtipoapos = '5' and rh01_reajusteparidade = 0 then '0106'
                WHEN rh02_rhtipoapos = '5' and rh01_reajusteparidade = 1 then '0106'
                WHEN rh02_rhtipoapos = '5' and rh01_reajusteparidade = 2 then '0105'
                WHEN rh02_rhtipoapos = '6' then '1001'
                ELSE '0'
            END)
        FROM
          dados_atualizar
        WHERE
          rh01_regist = rh02_regist and
          rh01_instit = rh02_instit and
          rh02_rhtipoapos != '0';

        ALTER TABLE pessoal.rhpessoalmov
            ADD CONSTRAINT rhpessoalmov_rhtipoapos_fk FOREIGN KEY (rh02_rhtipoapos)
            REFERENCES pessoal.rhtipoapos;

        alter table pessoal.rhpessoalmov enable trigger tg_pessoalmov_alt;

        -- ADICIONANDO OS CAMPOS AO DICIONARIO DE DADOS
        insert into configuracoes.db_syscampo values(1013963,'rh02_descinstrumento','varchar(255)','Descrição do instrumento','', 'Descrição do instrumento',255,'t','t','f',0,'text','Descrição do instrumento');
        insert into configuracoes.db_syscampo values(1013964,'rh02_sitpagbeneficio','bool','Pensão concedida por determinação Judicial','f', 'Pensão concedida Judicial',1,'f','f','f',5,'text','Pensão concedida Judicial');
        insert into configuracoes.db_sysarqcamp values(1158,1013963,36,0);
        insert into configuracoes.db_sysarqcamp values(1158,1013964,37,0);


        -- ALTERANDO TIPO DE DADO NO DICIONARIO DE DADOS PARA OS CAMPOS JA EXISTENTES
        update configuracoes.db_syscampo set nomecam = 'rh02_rhtipoapos', conteudo = 'varchar(4)', descricao = 'Tipo de Apos./Pensão', valorinicial = '0', rotulo = 'Tipo de Apos./Pensão', nulo = 't', tamanho = 4, maiusculo = 'f', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Tipo de Apos./Pensão' where codcam = 15614;
        update configuracoes.db_syscampo set nomecam = 'rh88_sequencial', conteudo = 'varchar(4)', descricao = 'Sequencial', valorinicial = '0', rotulo = 'Sequencial', nulo = 'f', tamanho = 4, maiusculo = 'f', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Sequencial' where codcam = 15612;
        update configuracoes.db_syscampo set nomecam = 'rh88_descricao', conteudo = 'varchar(200)', descricao = 'Descrição', valorinicial = '0', rotulo = 'Descrição', nulo = 'f', tamanho = 200, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Descrição' where codcam = 15613;
SQL;
        DB::connection()->getPdo()->exec($sql);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $sql = <<<SQL
        alter table pessoal.rhpessoalmov disable trigger tg_pessoalmov_alt;

        ALTER TABLE pessoal.rhpessoalmov drop CONSTRAINT rhpessoalmov_rhtipoapos_fk;
        delete from pessoal.rhtipoapos;
        alter table pessoal.rhtipoapos ALTER COLUMN rh88_descricao TYPE varchar(50);

        insert into pessoal.rhtipoapos values('0', 'Nenhum');
        insert into pessoal.rhtipoapos values('1', 'Morte');
        insert into pessoal.rhtipoapos values('2', 'Tempo de Contribuição');
        insert into pessoal.rhtipoapos values('3', 'Idade');
        insert into pessoal.rhtipoapos values('4', 'Invalidez');
        insert into pessoal.rhtipoapos values('5', 'Compulsória');
        insert into pessoal.rhtipoapos values('6', 'Professor');
        insert into pessoal.rhtipoapos values('7', 'Atividades de risco');
        insert into pessoal.rhtipoapos values('8', 'Agentes Nocivos');
        insert into pessoal.rhtipoapos values('9', 'Pensão dependente menor');
        insert into pessoal.rhtipoapos values('10', 'Sentença Judicial');
        insert into pessoal.rhtipoapos values('11', 'Deficiência');
        insert into pessoal.rhtipoapos values('12', 'Judicial');
        insert into pessoal.rhtipoapos values('13', 'Especial');
        insert into pessoal.rhtipoapos values('14', 'Outros');


        update pessoal.rhpessoalmov set rh02_rhtipoapos = '1' where rh02_rhtipoapos in ('0601', '0603');
        update pessoal.rhpessoalmov set rh02_rhtipoapos = '2' where rh02_rhtipoapos in ('0101', '0102');
        update pessoal.rhpessoalmov set rh02_rhtipoapos = '3' where rh02_rhtipoapos in ('0102', '0103');
        update pessoal.rhpessoalmov set rh02_rhtipoapos = '4' where rh02_rhtipoapos in ('0302', '0301');
        update pessoal.rhpessoalmov set rh02_rhtipoapos = '5' where rh02_rhtipoapos in ('0106', '0105');
        update pessoal.rhpessoalmov set rh02_rhtipoapos = '6' where rh02_rhtipoapos in ('1001');
        update pessoal.rhpessoalmov set rh02_rhtipoapos = '14' where rh02_rhtipoapos not in ('0', '1', '2', '3', '4', '5', '6');

        alter table pessoal.rhtipoapos ALTER COLUMN rh88_sequencial TYPE integer USING (rh88_sequencial::integer);

        alter table pessoal.rhpessoalmov ALTER COLUMN rh02_rhtipoapos TYPE integer USING (rh02_rhtipoapos::integer);

        ALTER TABLE pessoal.rhpessoalmov ADD CONSTRAINT rhpessoalmov_rhtipoapos_fk FOREIGN KEY (rh02_rhtipoapos) REFERENCES pessoal.rhtipoapos;
        alter table pessoal.rhpessoalmov enable trigger tg_pessoalmov_alt;

        -- ROLLBACK dos Elementos alterados no Dicionario de dados.
        update configuracoes.db_syscampo set nomecam = 'rh02_rhtipoapos', conteudo = 'int4', descricao = 'Tipo de Apos./Pensão', valorinicial = '0', rotulo = 'Tipo de Apos./Pensão', nulo = 't', tamanho = 4, maiusculo = 'f', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Tipo de Apos./Pensão' where codcam = 15614;
        update configuracoes.db_syscampo set nomecam = 'rh88_sequencial', conteudo = 'int4', descricao = 'Sequencial', valorinicial = '0', rotulo = 'Sequencial', nulo = 'f', tamanho = 4, maiusculo = 'f', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Sequencial' where codcam = 15612;
        update configuracoes.db_syscampo set nomecam = 'rh88_descricao', conteudo = 'varchar(50)', descricao = 'Descrição', valorinicial = '0', rotulo = 'Descrição', nulo = 'f', tamanho = 50, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Descrição' where codcam = 15613;

        -- APAGANDO CAMPOS DO DICIONARIO DE DADOS
        DELETE FROM configuracoes.db_sysarqcamp where codcam IN(1013963, 1013964);
        DELETE FROM configuracoes.db_syscampo WHERE codcam IN(1013963, 1013964);

        alter table pessoal.rhpessoalmov drop COLUMN rh02_descinstrumento;
        alter table pessoal.rhpessoalmov drop COLUMN rh02_sitpagbeneficio;


SQL;
        DB::connection()->getPdo()->exec($sql);

    }
}
