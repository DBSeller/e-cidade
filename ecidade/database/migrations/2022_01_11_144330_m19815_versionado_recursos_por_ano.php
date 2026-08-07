<?php

use App\Domain\Financeiro\Orcamento\Models\RecursoDetalhamento;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M19815VersionadoRecursosPorAno extends Migration
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
        $this->upMigracao();
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
        $this->downMigracao();
    }

    private function upDicionario()
    {
        DB::connection()->getPdo()->exec(<<<SQL
insert into db_sysarquivo
values (1010848, 'classificacaofr', 'Classificação das fontes de recrusos', 'o165', '2022-01-12', 'classificacaofr', 0, 'f', 'f', 'f', 'f' ),
       (1010849, 'fonterecurso', 'Versionamento da fonte de recurso por exercício', 'o166', '2022-01-12', 'Fonte de Recurso', 0, 'f', 'f', 'f', 'f' ),
       (1010850, 'fontesiconfi', 'tipos de fontes de recursos válidos', 'o167', '2022-01-12', 'fontes de recusos siconf', 0, 'f', 'f', 'f', 'f' );

insert into db_sysarqmod
values (35,1010848),
       (35,1010849),
       (35,1010850);

insert into db_syscampo
values (1013600,'orctiporec_id','int4','Código do Recurso no e-cidade','0', 'Código Recurso',10,'f','f','f',1,'text','Código Recurso'),
       (1013601,'codigo_siconfi','varchar(15)','Código do siconfi no exercício','', 'Código Siconfi',15,'f','f','f',0,'text','Código Siconfi'),
       (1013602,'gestao','varchar(5)','Fonte Recurso na gestão','', 'Fonte Recurso',5,'f','f','f',0,'text','Fonte Recurso'),
       (1013603,'classificacaofr_id','int4','Vínculo com Classificação','0', 'Classificação',10,'f','f','f',1,'text','Classificação'),
       (1013604,'tipo_detalhamento','char(2)','Detalhamento','00', 'Detalhamento',2,'f','f','f',1,'text','Detalhamento');

insert into db_sysarqcamp
values (1010848,1011345,1,0),
       (1010848,750,2,0),
       (1010849,1011345,1,0),
       (1010849,1013600,2,0),
       (1010849,15983,3,0),
       (1010849,1013601,4,0),
       (1010849,1013602,5,0),
       (1010849,1013603,6,0),
       (1010849,1013604,7,0),
       (1010849,750,8,0),
       (1010850,1013601,1,0),
       (1010850,750,2,0),
       (1010850,1013603,3,0);

insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010848,1011345,1,750);
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010849,1011345,1,1011345);
insert into db_sysprikey (codarq,codcam,sequen,camiden) values(1010850,1013601,1,750);

insert into db_sysforkey
values (1010849,1013600,1,749,0),
       (1010849,1013603,1,1010848,0),
       (1010850,1013603,1,1010848,0);

insert into db_sysindices
values (1008704,'fonterecurso_orctiporec_in',1010849,'0'),
       (1008705,'fonterecurso_classificacaofr_in',1010849,'0'),
       (1008706,'fontesiconfi_classificacaofr_in',1010850,'0');

insert into db_syscadind
values (1008704,1013600,1),
       (1008705,1013603,1),
       (1008706,1013603,1);
SQL
        );
    }

    private function upEstrutura()
    {
        DB::connection()->getPdo()->exec(<<<SQL
create table orcamento.classificacaofr (
  id serial primary key,
  descricao varchar(100)
);

select configuracoes.fc_auditoria_cria_funcao('orcamento.classificacaofr');

create table orcamento.fonterecurso (
  id serial primary key,
  orctiporec_id integer not null,
  exercicio integer not null,
  codigo_siconfi varchar(15) not null,
  gestao varchar(5) not null,
  classificacaofr_id integer not null,
  tipo_detalhamento  char(2) not null,
  descricao varchar(255),
  foreign key (orctiporec_id) references orcamento.orctiporec on delete cascade,
  foreign key (classificacaofr_id) references orcamento.classificacaofr
);

select configuracoes.fc_auditoria_cria_funcao('orcamento.fonterecurso');

create table orcamento.fontesiconfi (
  codigo_siconfi varchar(4) primary key,
  descricao varchar(255),
  classificacaofr_id integer,
  foreign key (classificacaofr_id) references orcamento.classificacaofr
);

select configuracoes.fc_auditoria_cria_funcao('orcamento.fontesiconfi');
SQL
        );
    }

    private function upMigracao()
    {
        DB::connection()->getPdo()->exec(<<<SQL
insert into orcamento.classificacaofr (id, descricao)
values (1, 'Não se aplica'),
       (2, 'Recursos Livres (não vinculados)'),
       (3, 'Recursos Vinculados à Educação'),
       (4, 'Recursos Vinculados à Saude'),
       (5, 'Recursos Vinculados à Assistência Social'),
       (6, 'Demais Vinculações Decorrentes de Transferências'),
       (7, 'Demais Vinculações Legais'),
       (8, 'Recursos Vinculados à Previdência social'),
       (9, 'Recursos Extraorçamentários'),
       (10, 'Outras Vinculações');

insert into orcamento.fonterecurso (orctiporec_id, exercicio, codigo_siconfi, gestao, classificacaofr_id, tipo_detalhamento, descricao)
select o15_codigo,
       2021,
       o15_codigosiconfi,
       o15_recurso,
       1,
       case when o15_loatipo is null or o15_loatipo = 0 then '00' else lpad(o15_loatipo, 2, 0) end as detalhamento,
       o15_descr
  from orctiporec;

insert into orcamento.fonterecurso (orctiporec_id, exercicio, codigo_siconfi, gestao, classificacaofr_id, tipo_detalhamento, descricao)
select o15_codigo,
       2022,
       o15_codigosiconfi,
       o15_recurso,
       1,
       case when o15_loatipo is null or o15_loatipo = 0 then '00' else lpad(o15_loatipo, 2, 0) end as detalhamento,
       o15_descr
  from orctiporec;

insert into fontesiconfi
values ('1500','Recursos não vinculados de Impostos', 2),
       ('1501','Outros Recursos não Vinculados', 2),
       ('1540','Transferências do FUNDEB - Impostos e Transferências de Impostos', 3),
       ('1541','Transferências do FUNDEB - Complementação da União - VAAF', 3),
       ('1542','Transferências do FUNDEB - Complementação da União - VAAT', 3),
       ('1543','Transferências do FUNDEB - Complementação da União - VAAR', 3),
       ('1544','Recursos de Precatórios do FUNDEF', 3),
       ('1550','Transferência do Salário-Educação', 3),
       ('1551','Transferências de Recursos do FNDE Referentes ao Programa Dinheiro Direto na Escola (PDDE)', 3),
       ('1552','Transferências de Recursos do FNDE Referentes ao Programa Nacional de Alimentação Escolar (PNAE)', 3),
       ('1553','Transferências de Recursos do FNDE Referentes ao Programa Nacional de Apoio ao Transporte Escolar (PNATE)', 3),
       ('1569','Outras Transferências de Recursos do FNDE', 3),
       ('9570','Transferências do Governo Federal referentes a Convênios e outros Repasses vinculados à Educação', 3),
       ('9571','Transferências do Estado referentes a Convênios e outros Repasses vinculados à Educação', 3),
       ('9572','Transferências de Municípios referentes a Convênios e outros Repasses vinculados à Educação', 3),
       ('1573','Royalties do Petróleo e Gás Natural Vinculados à Educação', 3),
       ('9574','Operações de Crédito Vinculadas à Educação', 3),
       ('9575','Outras Transferências de Convênios e Instrumentos Congêneres vinculados à Educação', 3),
       ('1576','Transferências de Recursos dos Estados para programas de educação', 3),
       ('1599','Outros Recursos Vinculados à Educação', 3),
       ('1600','Transferências Fundo a Fundo de Recursos do SUS provenientes do Governo Federal - Bloco de Manutenção das Ações e Serviços Públicos de Saúde',4),
       ('1601','Transferências Fundo a Fundo de Recursos do SUS provenientes do Governo Federal - Bloco de Estruturação da Rede de Serviços Públicos de Saúde',4),
       ('1602','Transferências Fundo a Fundo de Recursos do SUS provenientes do Governo Federal - Bloco de Manutenção das Ações e Serviços Públicos de Saúde - Recursos destinados ao enfrentamento da COVID-19 no bojo da ação 21C0.',4),
       ('1603','Transferências Fundo a Fundo de Recursos do SUS provenientes do Governo Federal - Bloco de Estruturação da Rede de Serviços Públicos de Saúde - Recursos destinados ao enfrentamento da COVID-19 no bojo da ação 21C0.',4),
       ('1621','Transferências Fundo a Fundo de Recursos do SUS provenientes do Governo Estadual',4),
       ('1622','Transferências Fundo a Fundo de Recursos do SUS provenientes dos Governos Municipais',4),
       ('9631','Transferências do Governo Federal referentes a Convênios e outros Repasses vinculados à Saúde',4),
       ('9632','Transferências do Estado referentes a Convênios e outros Repasses vinculados à Saúde',4),
       ('9633','Transferências de Municípios referentes a Convênios e outros Repasses vinculados à Saúde',4),
       ('9634','Operações de Crédito vinculadas à Saúde',4),
       ('1635','Royalties do Petróleo e Gás Natural vinculados à Saúde',4),
       ('9636','Outras Transferências de Convênios e Instrumentos Congêneres vinculados à Saúde',4),
       ('1659','Outros Recursos Vinculados à Saúde',4),
       ('1660','Transferência de Recursos do Fundo Nacional de Assistência Social - FNAS', 5),
       ('1661','Transferência de Recursos dos Fundos Estaduais de Assistência Social', 5),
       ('9665','Transferências de Convênios e outros Repasses vinculados à Assistência Social', 5),
       ('1669','Outros Recursos Vinculados à Assistência Social', 5),
       ('9700','Outras Transferências de Convênios ou Repasses da União', 6),
       ('9701','Outras Transferências de Convênios ou Repasses dos Estados', 6),
       ('9702','Outras Transferências de Convênios ou Repasses dos Municípios', 6),
       ('9703','Outras Transferências de Convênios ou Contratos de Repasse de outras Entidades', 6),
       ('1704','Transferência da União Referente a Royalties do Petróleo e Gás Natural', 6),
       ('1705','Transferência dos Estados Referente a Royalties do Petróleo e Gás Natural', 6),
       ('1706','Transferência Especial da União', 6),
       ('1707','Transferências da União - inciso I do art. 5º da Lei Complementar 173/2020', 6),
       ('1708','Transferência da União Referente à Compensação Financeira de Recursos Minerais', 6),
       ('1709','Transferência da União referente à Compensação Financeira de Recursos Hídricos', 6),
       ('1710','Transferência Especial dos Estados', 6),
       ('1749','Outras vinculações de transferências', 6),
       ('1750','Recursos da Contribuição de Intervenção no Domínio Econômico - CIDE', 7),
       ('1751','Recursos da Contribuição para o Custeio do Serviço de Iluminação Pública - COSIP', 7),
       ('1752','Recursos Vinculados ao Trânsito', 7),
       ('1753','Recursos provenientes de taxas e contribuições', 7),
       ('9754','Recursos de Operações de Crédito', 7),
       ('1755','Recursos de Alienação de Bens/Ativos - Administração Direta', 7),
       ('1756','Recursos de Alienação de Bens/Ativos - Administração Indireta', 7),
       ('1757','Recursos de depósitos judiciais - Lides das quais o ente faz parte', 7),
       ('1758','Recursos de depósitos judiciais - Lides das quais o ente não faz parte', 7),
       ('1759','Recursos vinculados a fundos', 7),
       ('1760','Recursos de Emolumentos e Taxas judiciais', 7),
       ('1761','Recursos vinculados ao Fundo de Combate e Erradicação da Pobreza', 7),
       ('1799','Outras vinculações legais', 7),
       ('1800','Recursos vinculados ao RPPS - Fundo em Capitalização (Plano Previdenciário)', 8),
       ('1801','Recursos vinculados ao RPPS - Fundo em Repartição (Plano Financeiro)', 8),
       ('1802','Recursos vinculados ao RPPS - Taxa de Administração', 8),
       ('1803','Recursos vinculados ao Sistema de Proteção Social dos Militares (SPSM)', 8),
       ('1860','Recursos extraorçamentários vinculados a precatórios', 9),
       ('1861','Recursos extraorçamentários vinculados a depósitos judiciais', 9),
       ('1862','Depósitos de terceiros', 9),
       ('1869','Outros recursos extraorçamentários', 9),
       ('1880','Recursos próprios dos consórcios', 10),
       ('1898','Recursos não classificados - a classificar', 10),
       ('1899','Outros Recursos Vinculados', 10);

insert into contabilidade.conplanoinfocomplementar
select 60,
       'CO',
       c121_descricao,
       c121_sql,
       c121_ajuda,
       c121_nomepropriedade,
       c121_valorpadrao
from conplanoinfocomplementar where c121_sigla = 'CF';
SQL
        );

        $uf = \App\Domain\Configuracao\Instituicao\Model\DBConfig::query()
            ->select('uf')
            ->whereRaw('prefeitura is true')
            ->first()
            ->uf;
        $detalhamento = RecursoDetalhamento::query()
            ->where('o203_codigo', 0)
            ->where('o203_estado', $uf)
            ->first();

        if (is_null($detalhamento)) {
            $detalhamento = new RecursoDetalhamento();
            $detalhamento->o203_codigo = 0;
            $detalhamento->o203_descricao = 'Sem Detalhamento';
            $detalhamento->o203_estado = $uf;
            $detalhamento->save();
        }
    }

    private function downDicionario()
    {
        DB::connection()->getPdo()->exec(<<<SQL
delete from db_sysprikey where codarq in (1010848, 1010849, 1010850);
delete from db_sysforkey where codarq in (1010848, 1010849, 1010850);
delete from db_sysarqcamp where codarq in (1010848, 1010849, 1010850);
delete from db_syscadind where codind in (1008704, 1008705, 1008706);
delete from db_sysindices where codind in (1008704, 1008705, 1008706);
delete from db_syscampo where codcam in (1013600, 1013601, 1013602, 1013603, 1013604);
delete from db_sysarqmod where codarq in (1010848, 1010849, 1010850);
delete from db_sysarquivo where codarq in (1010848, 1010849, 1010850);
SQL
        );
    }

    private function downEstrutura()
    {
        DB::connection()->getPdo()->exec(<<<SQL
drop table if exists orcamento.fonterecurso;
drop table if exists orcamento.fontesiconfi;
drop table if exists orcamento.classificacaofr;
SQL
        );
    }

    private function downMigracao()
    {
        DB::connection()->getPdo()->exec(<<<SQL
delete from contabilidade.conplanoinfocomplementar where c121_sigla = 'CO';
SQL
        );
    }
}
