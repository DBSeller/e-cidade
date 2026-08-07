<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M19852S2240CondicoesAmbientaisTrabalhoDicionarioDados extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $sSql = <<<SQL
insert into db_syscampo (codcam,nomecam,conteudo,descricao,valorinicial,rotulo,tamanho,nulo,maiusculo,autocompl,aceitatipo,tipoobj,rotulorel)
values (1013657,'rh55_tipolocal','int4','Tipo de local de Trabalho','0', 'Tipo de local de Trabalho',1,'f','f','f',1,'text','Tipo de local de Trabalho'),
       (1013658,'rh55_endereco','varchar(80)','Endereço do local ','', 'Endereço',80,'f','t','f',0,'text','Endereço'),
       (1013659,'rh55_tipoestabelecimento','int4','Tipo de Estabelecimento','0', 'Tipo de Estabelecimento',1,'f','f','f',1,'text','Tipo de Estabelecimento'),
       (1013660,'rh55_tipoinscricao','int4','Tipo de inscrição','0', 'Tipo de inscrição',1,'f','f','f',1,'text','Tipo de inscrição'),
       (1013661,'rh55_numeroinscricao','varchar(14)','Numero de Inscrição','', 'Numero de Inscrição',14,'f','f','f',1,'text','Numero de Inscrição'),
       (1013662,'rh55_observacaoregistrosambientais','text','Observações registros ambientais','', 'Observações registros ambientais',1,'t','t','f',0,'text','Observações registros ambientais');

insert into db_syscampodef (codcam,defcampo,defdescr)
values (1013657,'1','Urbano'),
       (1013657,'2','Rural'),
       (1013659,'1','1 - Estabelecimento do próprio empregador'),
       (1013659,'2','2 - Estabelecimento de Terceiros'),
       (1013660,'1','1 - CNPJ'),
       (1013660,'3','3 - CAEPF'),
       (1013660,'4','4 - CNO');

insert into db_sysarquivo (codarq,nomearq,descricao,sigla,dataincl,rotulo,tipotabela,naolibclass,naolibfunc,naolibprog,naolibform)
values (1010858,'rhlocaltrabagentesnocivos','Informações dos agentes nocivos do local de trabalho','rh256','2022-02-04','Agentes nocivos do local de trabalho',0,'f','f','f','f'),
       (1010859,'rhlocaltrabequipamentoprotecao','Equipamentos de proteção do local de trabalho','rh257','2022-02-04','Equipamentos de proteção do local de trabalho',0,'f','f','f','f'),                          
       (1010860,'rhlocaltrabregistroambiental','Registros ambientais do local de trabalho','rh258','2022-02-04','Registros ambientais do local de trabalho',0,'f','f','f','f'),
       (1010861, 'rhlocaltrabequipamentoprotecaoepi', 'EPI do Local de Trabalho', 'rh259', '2022-02-06', 'EPI do Local de Trabalho', 0, 'f', 'f', 'f', 'f' );
                          
insert into db_sysarqmod (codmod,codarq)
values (28, 1010858),
       (28, 1010859),
       (28, 1010860),
       (28,1010861);
                  
insert into db_sysarqarq (codarqpai,codarq)   
values (0,1010858),
       (0,1010859),
       (0,1010860);

insert into db_syscampo (codcam,nomecam,conteudo,descricao,valorinicial,rotulo,tamanho,nulo,maiusculo,autocompl,aceitatipo,tipoobj,rotulorel)
values (1013663,'rh256_sequencial','int4','Sequencial','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial'),
       (1013664,'rh256_rhlocaltrab','int4','Local de trabalho','0', 'Local de trabalho',10,'f','f','f',1,'text','Local de trabalho'),
       (1013665,'rh256_instituicao','int4','Instituiçao','0', 'Instituiçao',10,'f','f','f',1,'text','Instituiçao'),
       (1013666,'rh256_agentequimico','varchar(10)','Agente químico','', 'Agente químico',10,'t','t','f',0,'text','Agente químico'),
       (1013667,'rh256_agentefisico','varchar(10)','Agente físico','', 'Agente físico',10,'t','t','f',0,'text','Agente físico'),
       (1013668,'rh256_agentebiologico','varchar(10)','Agente biológico','', 'Agente biológico',10,'t','t','f',0,'text','Agente biológico'),
       (1013669,'rh256_associacaoagente','varchar(10)','Associação de Agentes Nocivos','', 'Associação de Agentes Nocivos',10,'t','t','f',0,'text','Associação de Agentes Nocivos'),
       (1013670,'rh256_outroagente','varchar(10)','Outros Agentes Nocivos','', 'Outros Agentes Nocivos',10,'t','t','f',0,'text','Outros Agentes Nocivos'),
       (1013671,'rh256_ausenciaagente','varchar(10)','Ausencia de Agentes Nocivos','', 'Ausencia de Agentes Nocivos',10,'t','t','f',0,'text','Ausencia de Agentes Nocivos'),
       (1013672,'rh256_tipoavaliacao','int4','Tipo de Avaliação do Agente Nocivo','0', 'Tipo de Avaliação do Agente Nocivo',1,'t','f','f',1,'text','Tipo de Avaliação do Agente Nocivo'),
       (1013673,'rh256_intensidadeconcentracao','varchar(10)','Intensidade, concentração ou dose da exposição do trabalhador ao agente nocivo, caso seja quantitativo','', 'Intensidade, concentracao ou dose',10,'t','t','f',0,'text','Intensidade, concentracao ou dose'),
       (1013674,'rh256_tolerancialimite','varchar(10)','Limite de tolerância calculado para agentes específicos','', 'Limite de tolerância',10,'t','t','f',0,'text','Limite de tolerância'),
       (1013675,'rh256_medida','varchar(3)','Dose ou unidade de medida da intensidade ou concentração do agente','', 'Medida',3,'t','t','f',0,'text','Medida'),
       (1013676,'rh256_tecnicamedicao','varchar(40)','Técnica utilizada para medição da intensidade ou concentração','', 'Técnica utilizada',40,'t','t','f',0,'text','Técnica utilizada');
       
insert into db_syscampo (codcam,nomecam,conteudo,descricao,valorinicial,rotulo,tamanho,nulo,maiusculo,autocompl,aceitatipo,tipoobj,rotulorel)
values (1013677,'rh257_sequencial','int4','Sequencial','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial'),
       (1013678,'rh257_rhlocaltrab','int4','Local de trabalho','0', 'Local de trabalho',10,'f','f','f',1,'text','Local de trabalho'),
       (1013679,'rh257_instituicao','int4','Instituição','0', 'Instituição',10,'f','f','f',1,'text','Instituição'),
       (1013680,'rh257_utilizaepc','int4','Utilização EPC','0', 'Utilização EPC',1,'f','f','f',1,'text','Utilização EPC'),
       (1013681,'rh257_eficaciaepc','varchar(1)','Os EPCs são eficazes na neutralização do risco ao trabalhador?','', 'Eficacia EPC',1,'f','t','f',0,'text','Eficacia EPC'),
       (1013682,'rh257_utilizaepi','int4','Utiliza EPI?','0', 'Utiliza EPI?',1,'f','f','f',1,'text','Utiliza EPI?'),
       (1013683,'rh257_eficaciaepi','varchar(1)','Os EPIs são eficazes na neutralização do risco ao trabalhador?','', 'Eficacia EPI',1,'f','t','f',0,'text','Eficacia EPI'),
       (1013686,'rh257_medidaprotecaoepi','varchar(1)','Foi tentada a implementação de medidas de proteção coletiva, de caráter administrativo ou de organização, optando-se pelo EPI por inviabilidade técnica, insuficiência ou interinidade, ou ainda em caráter complementar ou emergencial?','', 'Implementada medida de proteção?',1,'f','t','f',0,'text','Implementada medida de proteção?'),
       (1013687,'rh257_funcionamentoepi','varchar(1)','Foram observadas as condições de funcionamento do EPI ao longo do tempo, conforme especificação técnica do fabricante nacional ou importador, ajustadas às condições de campo?','', 'Observado funcionamento Epi?',1,'f','t','f',0,'text','Observado funcionamento Epi?'),
       (1013688,'rh257_usoininterruptoepi','varchar(1)','Foi observado o uso ininterrupto do EPI ao longo do tempo, conforme especificação técnica do fabricante nacional ou importador, ajustadas às condições de campo?','', 'Uso ininterrupto?',1,'f','t','f',0,'text','Uso ininterrupto?'),
       (1013689,'rh257_validadeepi','varchar(1)','Foi observado o prazo de validade do CA no momento da compra do EPI?','', 'Observada Validade?',1,'f','t','f',0,'text','Observada Validade?'),
       (1013690,'rh257_periodicidadeepi','varchar(1)','É observada a periodicidade de troca definida pelo fabricante nacional ou importador e/ou programas ambientais,comprovada mediante recibo assinado pelo usuário em época própria?','', 'Observada Periodicidade?',1,'f','t','f',0,'text','Observada Periodicidade?'),
       (1013691,'rh257_higienizacaoepi','varchar(1)','É observada a higienização conforme orientação do fabricante nacional ou importador?','', 'Observada higienização?',1,'f','t','f',0,'text','Observada higienização?');

insert into db_syscampo (codcam,nomecam,conteudo,descricao,valorinicial,rotulo,tamanho,nulo,maiusculo,autocompl,aceitatipo,tipoobj,rotulorel)
values (1013692,'rh258_sequencial','int4','Sequencial','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial'),
       (1013693,'rh258_rhlocaltrab','int4','Local de trabalho','0', 'Local de trabalho',10,'f','f','f',1,'text','Local de trabalho'),
       (1013694,'rh258_instituicao','int4','Instituição','0', 'Instituição',10,'f','f','f',1,'text','Instituição'),
       (1013695,'rh258_cpfresponsavel','varchar(11)','Cpf responsável','', 'Cpf responsável',11,'f','t','f',0,'text','Cpf responsável'),
       (1013696,'rh258_identificacaoorgao','int4','Identificação do Orgão','0', 'Identificação do Orgão',1,'f','f','f',1,'text','Identificação do Orgão'),
       (1013697,'rh258_numeroinscricaoorgao','varchar(14)','Número de inscrição no órgão','', 'Número de inscrição no órgão',14,'f','t','f',0,'text','Número de inscrição no órgão de classe'),
       (1013698,'rh258_descricaoorgao','varchar(20)','Descrição (sigla) órgão','', 'Descrição (sigla) órgão',20,'t','t','f',0,'text','Descrição (sigla) órgão vinculado'),
       (1013699,'rh258_uforgao','varchar(2)','UF do órgão de classe','', 'UF do órgão de classe',2,'f','f','f',2,'text','UF do órgão de classe'),
       (1013700,'rh258_periodoinicial','date','Período avaliação inicial','null', 'Período avaliação inicial',10,'f','f','f',0,'text','Período de avaliação inicial'),
       (1013701,'rh258_periodofinal','date','Período avaliação final','null', 'Período avaliação final',10,'t','f','f',0,'text','Período de avaliação final');

insert into db_syscampo (codcam,nomecam,conteudo,descricao,valorinicial,rotulo,tamanho,nulo,maiusculo,autocompl,aceitatipo,tipoobj,rotulorel)
values (1013702,'rh259_sequencial','int4','Sequencial','0', 'Sequencial',10,'f','f','f',1,'text','Sequencial'),
       (1013703,'rh259_rhlocaltrabequipamentoprotecao','int4','Sequencial equipamento proteção','0', 'Sequencial equipamento proteção',10,'f','f','f',1,'text','Sequencial equipamento proteção'),
       (1013704,'rh259_documentoavaliacao','varchar(255)','Certificado de Aprovação ou Documento de Avaliação','', 'CA ou Documento de Avaliação',255,'f','t','f',0,'text','CA ou Documento de Avaliação'),
       (1013705,'rh259_descricao','text','Descrição','', 'Descrição',1,'f','t','f',0,'text','Descrição');

insert into db_syscampodef (codcam,defcampo,defdescr) 
values (1013672,'1','1 - Critério quantitativo'),
       (1013672,'2','2 - Critério qualitativo'),
       (1013680,'0','Não se aplica'),
       (1013680,'1','Não implementada'),
       (1013680,'2','Implementada'),
       (1013681,'S','SIM'),
       (1013681,'N','NÃO'),
       (1013682,'0','Não se aplica'),
       (1013682,'1','Não implementada'),
       (1013682,'2','Implementada'),
       (1013683,'S','SIM'),
       (1013683,'N','NÃO'),
       (1013686,'S','SIM'),
       (1013686,'N','NÃO'),
       (1013687,'S','SIM'),
       (1013687,'N','NÃO'),
       (1013688,'S','SIM'),
       (1013688,'N','NÃO'),
       (1013689,'S','SIM'),
       (1013689,'N','NÃO'),
       (1013690,'S','SIM'),
       (1013690,'N','NÃO'),
       (1013691,'S','SIM'),
       (1013691,'N','NÃO'),
       (1013696,'1','1 - Conselho Regional de Medicina - CRM'),
       (1013696,'4','4 - Conselho Regional de Engenharia e Agronomia - CREA'),
       (1013696,'9','9 - Outros');

insert into db_sysarqcamp (codarq,codcam,seqarq,codsequencia)
values (1542,1013657,6,0),
       (1542,1013658,7,0),
       (1542,1013659,8,0),
       (1542,1013660,9,0),
       (1542,1013661,10,0),
       (1542,1013662,11,0);

insert into db_sysarqcamp (codarq,codcam,seqarq,codsequencia)
values (1010858,1013663,1,0),
       (1010858,1013664,2,0),
       (1010858,1013665,3,0),
       (1010858,1013666,4,0),
       (1010858,1013667,5,0),
       (1010858,1013668,6,0),
       (1010858,1013669,7,0),
       (1010858,1013670,8,0),
       (1010858,1013671,9,0),
       (1010858,1013672,10,0),
       (1010858,1013673,11,0),
       (1010858,1013674,12,0),
       (1010858,1013675,13,0),
       (1010858,1013676,14,0);

insert into db_sysarqcamp (codarq,codcam,seqarq,codsequencia)
values (1010859,1013677,1,0),
       (1010859,1013678,2,0),
       (1010859,1013679,3,0),
       (1010859,1013680,4,0),
       (1010859,1013681,5,0),
       (1010859,1013682,6,0),
       (1010859,1013683,7,0),
       (1010859,1013686,10,0),
       (1010859,1013687,11,0),
       (1010859,1013688,12,0),
       (1010859,1013689,13,0),
       (1010859,1013690,14,0),
       (1010859,1013691,15,0);

insert into db_sysarqcamp (codarq,codcam,seqarq,codsequencia)
values (1010860,1013692,1,0),
       (1010860,1013693,2,0),
       (1010860,1013694,3,0),
       (1010860,1013695,4,0),
       (1010860,1013696,5,0),
       (1010860,1013697,6,0),
       (1010860,1013698,7,0),
       (1010860,1013699,8,0),
       (1010860,1013700,9,0),
       (1010860,1013701,10,0);

insert into db_sysarqcamp (codarq,codcam,seqarq,codsequencia) 
values (1010861,1013702,1,0),
       (1010861,1013703,2,0),
       (1010861,1013704,3,0),
       (1010861,1013705,4,0);

insert into db_sysprikey (codarq,codcam,sequen,camiden) 
values (1010858,1013663,1,1013664),
       (1010859,1013677,1,1013678),
       (1010860,1013692,1,1013693),
       (1010861,1013702,1,1013704);

insert into db_sysindices (codind,nomeind,codarq,campounico)
values (1008711,'rhlocaltrabregistroambiental_rhlocaltrab_in',1010860,'0'), 
       (1008712,'rhlocaltrabequipamentoprotecao_rhlocaltrab_in',1010859,'0'),
       (1008713,'rhlocaltrabagentesnocivos_rhlocaltrab_in',1010858,'0'),
       (1008714,'rhlocaltrabequipamentoprotecaoepi_rhlocaltrabequipamentoprotecao_in',1010861,'0');

insert into db_syscadind (codind,codcam,sequen) 
values (1008711,1013693,1),
       (1008711,1013694,2),
       (1008712,1013678,1),
       (1008712,1013679,2),
       (1008713,1013664,1),
       (1008713,1013665,2),
       (1008714,1013703,1);

insert into db_sysforkey (codarq, codcam, sequen, referen, tipoobjrel)
values (1010858,1013664,1,1542,0),
       (1010858,1013665,2,1542,0),
       (1010859,1013678,1,1542,0),
       (1010859,1013679,2,1542,0),
       (1010860,1013693,1,1542,0),
       (1010860,1013694,2,1542,0),
       (1010861,1013703,1,1010859,0);


insert into db_syssequencia (codsequencia, nomesequencia, incrseq, minvalueseq, maxvalueseq, startseq, cacheseq)     
values (1001033, 'rhlocaltrabagentesnocivos_rh256_sequencial_seq', 1, 1, 9223372036854775807, 1, 1),
       (1001034, 'rhlocaltrabequipamentoprotecao_rh257_sequencial_seq', 1, 1, 9223372036854775807, 1, 1),
       (1001035, 'rhlocaltrabregistroambiental_rh258_sequencial_seq', 1, 1, 9223372036854775807, 1, 1),
       (1001037, 'rhlocaltrabequipamentoprotecaoepi_rh259_sequencial_seq', 1, 1, 9223372036854775807, 1, 1);

update db_sysarqcamp set codsequencia = 1001033 where codarq = 1010858 and codcam = 1013663;
update db_sysarqcamp set codsequencia = 1001034 where codarq = 1010859 and codcam = 1013677;
update db_sysarqcamp set codsequencia = 1001035 where codarq = 1010860 and codcam = 1013692;
update db_sysarqcamp set codsequencia = 1001037 where codarq = 1010861 and codcam = 1013702;
update db_sysarqcamp set codsequencia = 499     where codarq = 1542    and codcam = 9014;

update db_itensmenu set funcao = 'pes1_manutencaoRhLocalTrab.php?db_opcao=1' where id_item = 5216; 
update db_itensmenu set funcao = 'pes1_manutencaoRhLocalTrab.php?db_opcao=2' where id_item = 5217; 
update db_itensmenu set funcao = 'pes1_manutencaoRhLocalTrab.php?db_opcao=3' where id_item = 5218;
SQL;
        DB::connection()->getPdo()->exec($sSql);
    }

    public function down() 
    {
        
        $sSql = <<<SQL
update db_itensmenu set funcao = 'pes1_rhlocaltrab001.php' where id_item = 5216; 
update db_itensmenu set funcao = 'pes1_rhlocaltrab002.php' where id_item = 5217; 
update db_itensmenu set funcao = 'pes1_rhlocaltrab003.php' where id_item = 5218;

delete from db_syssequencia where codsequencia in (1001033,1001034,1001035,1001037);

delete from db_sysforkey where codarq in (1010858,1010859,1010860,1010861);

delete from db_syscadind where codind in (select codind from db_sysindices where codarq in (1010858,1010859,1010860,1010861));
delete from db_sysindices where codarq in (1010858,1010859,1010860,1010861);

delete from db_sysprikey where codarq in (1010858,1010859,1010860,1010861);

delete from db_sysarqcamp where codarq in (1010858,1010859,1010860,1010861); 
delete from db_sysarqcamp where codcam in (1013657,1013658,1013659,1013660,1013661,1013662);

delete from db_syscampodef where codcam in (1013657,1013658,1013659,1013660,1013661,1013662,1013663,1013664,1013665,1013666,1013667,1013668,
1013669,1013670,1013671,1013672,1013673,1013674,1013675,1013676,1013677,1013678,1013679,1013680,1013681,1013682,1013683,1013686,1013687,
1013688,1013689,1013690,1013691,1013692,1013693,1013694,1013695,1013696,1013697,1013698,1013699,1013700,1013701,1013702,1013703,1013704,1013705) ;

delete from db_syscampo where codcam in (1013657,1013658,1013659,1013660,1013661,1013662,1013663,1013664,1013665,1013666,1013667,1013668,
1013669,1013670,1013671,1013672,1013673,1013674,1013675,1013676,1013677,1013678,1013679,1013680,1013681,1013682,1013683,1013684,1013686,1013687,
1013688,1013689,1013690,1013691,1013692,1013693,1013694,1013695,1013696,1013697,1013698,1013699,1013700,1013701,1013702,1013703,1013704,1013705);

delete from db_sysarqarq where codarq in (1010858, 1010859, 1010860, 1010861); 
                              
delete from db_sysarqmod where codarq in (1010858, 1010859, 1010860, 1010861);

delete from db_sysarquivo where codarq in (1010858, 1010859, 1010860, 1010861);
SQL;
        DB::connection()->getPdo()->exec($sSql);
        
        
    }
}
