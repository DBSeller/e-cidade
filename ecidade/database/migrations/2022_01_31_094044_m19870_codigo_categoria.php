<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M19870CodigoCategoria extends Migration
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
        $this->upIncluirRegistros();
        $this->upAlteraRegistro();
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
    }

    private function upDicionario()
    {
        $sql = <<<SQL
        insert into configuracoes.db_sysarquivo values (1010857, 'rhcodigocategoria', 'Código Categoria Trabalhador/eSocial', 'rh255', '2022-01-31', 'Código categoria', 0, 'f', 'f', 'f', 'f' );
        insert into configuracoes.db_sysarqmod values (29,1010857);
        insert into configuracoes.db_syscampo values(1013654,'rh255_codigo','int4','Código de categoria do trabalhador.','0', 'Códgio Categoria',10,'f','f','f',1,'text','Códgio Categoria');
        insert into configuracoes.db_syscampo values(1013655,'rh255_descricao','varchar(255)','Descrição categoria do trabalhador','', 'Descrição Categoria',255,'f','t','f',0,'text','Descrição Categoria');
        delete from configuracoes.db_sysarqcamp where codarq = 1010857;
        insert into configuracoes.db_sysarqcamp values(1010857,1013654,1,0);
        insert into configuracoes.db_sysarqcamp values(1010857,1013655,2,0);
        delete from configuracoes.db_sysprikey where codarq = 1010857;
        insert into configuracoes.db_sysprikey (codarq,codcam,sequen,camiden) values(1010857,1013654,1,1013654);
        update configuracoes.db_syscampo set nomecam = 'rh255_descricao', conteudo = 'varchar(500)', descricao = 'Descrição categoria do trabalhador', valorinicial = '', rotulo = 'Descrição Categoria', nulo = 'f', tamanho = 500, maiusculo = 't', autocompl = 'f', aceitatipo = 0, tipoobj = 'text', rotulorel = 'Descrição Categoria' where codcam = 1013655;
        delete from configuracoes.db_syscampodep where codcam = 1013655;
        delete from configuracoes.db_syscampodef where codcam = 1013655;

        -- Inclusão de campo tabela pessoal.rhregime
        insert into configuracoes.db_syscampo values(1013656,'rh30_codigocategoria','int4','Código referente a categoria do trabalhador.','0', 'Código de Categoria',10,'f','f','f',1,'text','Código de Categoria');
        delete from configuracoes.db_sysarqcamp where codarq = 1183 and codcam = 1013656;
        insert into configuracoes.db_sysarqcamp values(1183,1013656,12,0);
        delete from configuracoes.db_sysforkey where codarq = 1183 and codcam = 1013656;
        insert into configuracoes.db_sysforkey values(1183,1013656,1,1010857,0);
        delete from configuracoes.db_sysforkey where codarq = 1183 and referen = 1010857;

SQL;
        DB::connection()->getPdo()->exec($sql);

    }

    private function downDicionario()
    {
        $sql = <<<SQL
        delete from configuracoes.db_sysprikey where codarq = 1010857 and codcam = 1013654;
        delete from configuracoes.db_sysarqcamp where codarq = 1010857 and codcam between 1013654 and 1013655;
        delete from configuracoes.db_syscampo where codcam between 1013654 and 1013655;
        delete from configuracoes.db_sysarqmod where codmod = 29 and codarq = 1010857;
        delete from configuracoes.db_sysarquivo where codarq = 1010857;

        -- Exclusao do campo  rh30_codigocategoria na tabela pessoal.rhregime
        delete from configuracoes.db_sysforkey where codarq = 1183 and codcam = 1013656;
        delete from configuracoes.db_sysarqcamp where codarq = 1183 and codcam = 1013656;
        delete from configuracoes.db_syscampo where codcam = 1013656;
SQL;
        DB::connection()->getPdo()->exec($sql);

    }

    private function upEstrutura()
    {
        $sql = <<<SQL
        -- Criando  sequences
        -- TABELAS E ESTRUTURA
        -- Módulo: recursoshumanos
        CREATE TABLE recursoshumanos.rhcodigocategoria(
        rh255_codigo		int4 NOT NULL default 0,
        rh255_descricao		varchar(500)  default '',
        CONSTRAINT rhcodigocategoria_codi_pk PRIMARY KEY (rh255_codigo));

        --Criação da coluna rh30_codigocategoria na tabela pessoal.rhregime
        ALTER TABLE pessoal.rhregime ADD rh30_codigocategoria int4 NULL;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function downEstrutura()
    {
        $sql = <<<SQL
        --DROP TABLE:
        DROP TABLE IF EXISTS recursoshumanos.rhcodigocategoria;

        -- Exclusão da coluna rh30_codigocategoria na tabela pessoal.rhregime
        ALTER TABLE pessoal.rhregime DROP rh30_codigocategoria;
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upIncluirRegistros()
    {
        $sql = <<<SQL
        INSERT INTO recursoshumanos.rhcodigocategoria
        (rh255_codigo, rh255_descricao)
        values (101, 'Empregado - Geral, inclusive o empregado público da administração direta ou indireta contratado pela CLT (EMPREGADO E TRAB.TEMPORÁRIO)'),
        (102, 'Empregado - Trabalhador rural por pequeno prazo da Lei 11.718/2008 (EMPREGADO E TRAB.TEMPORÁRIO)'),
        (103, 'Empregado - Aprendiz (EMPREGADO E TRAB.TEMPORÁRIO)'),
        (104, 'Empregado - Doméstico (EMPREGADO E TRAB.TEMPORÁRIO)'),
        (105, 'Empregado - Contrato a termo firmado nos termos da Lei 9.601/1998 (EMPREGADO E TRAB.TEMPORÁRIO)'),
        (106, 'Trabalhador temporário - Contrato nos termos da Lei 6.019/1974 (EMPREGADO E TRAB.TEMPORÁRIO)'),
        (107, 'Empregado - Contrato de trabalho Verde e Amarelo - sem acordo para antecipação mensal da multa rescisória do FGTS (EMPREGADO E TRAB.TEMPORÁRIO)'),
        (108, 'Empregado - Contrato de trabalho Verde e Amarelo - com acordo para antecipação mensal da multa rescisória do FGTS (EMPREGADO E TRAB.TEMPORÁRIO)'),
        (111, 'Empregado - Contrato de trabalho intermitente (EMPREGADO E TRAB.TEMPORÁRIO)'),
        (201, 'Trabalhador avulso portuário (TRABALHADOR AVULSO)'),
        (202, 'Trabalhador avulso não portuário (TRABALHADOR AVULSO)'),
        (301, 'Servidor público titular de cargo efetivo, magistrado, ministro de Tribunal de Contas, conselheiro de Tribunal de Contas e membro do Ministério Público (AGENTE PÚBLICO)'),
        (302, 'Servidor público ocupante de cargo exclusivo em comissão (AGENTE PÚBLICO)'),
        (303, 'Exercente de mandato eletivo (AGENTE PÚBLICO)'),
        (304, 'Servidor público exercente de mandato eletivo, inclusive com exercício de cargo em comissão (AGENTE PÚBLICO)'),
        (305, 'Servidor público indicado para conselho ou órgão deliberativo, na condição de representante do governo, órgão ou entidade da administração pública (AGENTE PÚBLICO)'),
        (306, 'Servidor público contratado por tempo determinado, sujeito a regime administrativo especial definido em lei própria (AGENTE PÚBLICO)'),
        (307, 'Militar (AGENTE PÚBLICO)'),
        (308, 'Conscrito (AGENTE PÚBLICO)'),
        (309, 'Agente público - Outros (AGENTE PÚBLICO)'),
        (310, 'Servidor público eventual (AGENTE PÚBLICO)'),
        (311, 'Ministros, juízes, procuradores, promotores ou oficiais de justiça à disposição da Justiça Eleitoral (AGENTE PÚBLICO)'),
        (312, 'Auxiliar local (AGENTE PÚBLICO)'),
        (313, 'Servidor público exercente de atividade de instrutoria, capacitação, treinamento, curso ou concurso, ou convocado para pareceres técnicos ou depoimentos (AGENTE PÚBLICO)'),
        (401, 'Dirigente sindical - Informação prestada pelo sindicato (CESSÃO)'),
        (410, 'Trabalhador cedido/exercício em outro órgão/juiz auxiliar - Informação prestada pelo cessionário/destino (CESSÃO)'),
        (501, 'Dirigente sindical - Segurado especial (SEGURADO ESPECIAL)'),
        (701, 'Contribuinte individual - Autônomo em geral, exceto se enquadrado em uma das demais categorias de contribuinte individual (CONTRIBUINTE INDIVIDUAL)'),
        (711, 'Contribuinte individual - Transportador autônomo de passageiros (CONTRIBUINTE INDIVIDUAL)'),
        (712, 'Contribuinte individual - Transportador autônomo de carga (CONTRIBUINTE INDIVIDUAL)'),
        (721, 'Contribuinte individual - Diretor não empregado, com FGTS (CONTRIBUINTE INDIVIDUAL)'),
        (722, 'Contribuinte individual - Diretor não empregado, sem FGTS (CONTRIBUINTE INDIVIDUAL)'),
        (723, 'Contribuinte individual - Empresário, sócio e membro de conselho de administração ou fiscal (CONTRIBUINTE INDIVIDUAL)'),
        (731, 'Contribuinte individual - Cooperado que presta serviços por intermédio de cooperativa de trabalho (CONTRIBUINTE INDIVIDUAL)'),
        (734, 'Contribuinte individual - Transportador cooperado que presta serviços por intermédio de cooperativa de trabalho (CONTRIBUINTE INDIVIDUAL)'),
        (738, 'Contribuinte individual - Cooperado filiado a cooperativa de produção (CONTRIBUINTE INDIVIDUAL)'),
        (741, 'Contribuinte individual - Microempreendedor individual (CONTRIBUINTE INDIVIDUAL)'),
        (751, 'Contribuinte individual - Magistrado classista temporário da Justiça do Trabalho ou da Justiça Eleitoral que seja aposentado de qualquer regime previdenciário (CONTRIBUINTE INDIVIDUAL)'),
        (761, 'Contribuinte individual - Associado eleito para direção de cooperativa, associação ou entidade de classe de qualquer natureza ou finalidade, bem como o síndico ou administrador eleito para exercer atividade de direção condominial, desde que recebam remuneração (CONTRIBUINTE INDIVIDUAL)'),
        (771, 'Contribuinte individual - Membro de conselho tutelar, nos termos da Lei 8.069/1990 (CONTRIBUINTE INDIVIDUAL)'),
        (781, 'Ministro de confissão religiosa ou membro de vida consagrada, de congregação ou de ordem religiosa (CONTRIBUINTE INDIVIDUAL)'),
        (901, 'Estagiário (BOLSISTA)'),
        (902, 'Médico residente ou residente em área profissional de saúde (BOLSISTA)'),
        (903, 'Bolsista (BOLSISTA)'),
        (904, 'Participante de curso de formação, como etapa de concurso público, sem vínculo de emprego/estatutário (BOLSISTA)');
SQL;
        DB::connection()->getPdo()->exec($sql);
    }

    private function upAlteraRegistro()
    {
        $sql = <<<SQL
        UPDATE pessoal.rhregime set rh30_codigocategoria =
        CASE
            WHEN rh30_regime = 1 then 301
            WHEN rh30_regime = 2 then 101
            WHEN rh30_regime = 3 then 302
            ELSE rh30_codigocategoria
        END;

SQL;
        DB::connection()->getPdo()->exec($sql);
    }


}
