<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20462RgfAnexoCinco extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
insert into orcparamrel (o42_codparrel, o42_descrrel, o42_orcparamrelgrupo, o42_notapadrao) values (266, 'ANEXO V - DEMONSTRATIVO DA DISPONIBILIDADE DE CAIXA E DOS RESTOS A PAGAR', 1, 'FONTE: Sistema E-Cidade, Unidade Responsável [nome_departamento], Data da emissão [data_emissao] e hora de emissão [hora_emissao]');
insert into orcparamrelperiodos (o113_sequencial, o113_periodo, o113_orcparamrel) values (nextval('orcparamrelperiodos_o113_sequencial_seq'), 13, 266);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (266, 1, 'TOTAL DOS RECURSOS NÃO VINCULADOS (I)', 0, 0, 0, 'f', 'f', 'f', 'f', 'f', 'TOTAL DOS RECURSOS NÃO VINCULADOS (I)', 'f', 't', 1, 1, '', 'f', 0);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (266, 2, 'Recursos Não Vinculados de Impostos', 0, 0, 0, 'f', 'f', 'f', 'f', 'f', 'Recursos Não Vinculados de Impostos', 't', 'f', 2, 1, '', 'f', 0);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (266, 3, 'Outros Recursos não Vinculados', 0, 0, 0, 'f', 'f', 'f', 'f', 'f', 'Outros Recursos não Vinculados', 't', 'f', 3, 1, '', 'f', 0);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (266, 4, 'TOTAL DOS RECURSOS VINCULADOS (II)', 0, 0, 0, 'f', 'f', 'f', 'f', 'f', 'TOTAL DOS RECURSOS VINCULADOS (II)', 'f', 't', 4, 1, '', 'f', 0);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (266, 5, 'Receitas de Impostos e de Transferência de Impostos - Educaç', 0, 0, 0, 'f', 'f', 'f', 'f', 'f', 'Receitas de Impostos e de Transferência de Impostos - Educação', 't', 'f', 5, 1, '', 'f', 0);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (266, 6, 'Transferências do FUNDEB', 0, 0, 0, 'f', 'f', 'f', 'f', 'f', 'Transferências do FUNDEB', 't', 'f', 6, 1, '', 'f', 0);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (266, 7, 'Outros Recursos Vinculados à Educação', 0, 0, 0, 'f', 'f', 'f', 'f', 'f', 'Outros Recursos Vinculados à Educação', 't', 'f', 7, 1, '', 'f', 0);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (266, 8, 'Receitas de Impostos e de Transferência de Impostos - Saúde', 0, 0, 0, 'f', 'f', 'f', 'f', 'f', 'Receitas de Impostos e de Transferência de Impostos - Saúde', 't', 'f', 8, 1, '', 'f', 0);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (266, 9, 'Outros Recursos Vinculados à Saúde', 0, 0, 0, 'f', 'f', 'f', 'f', 'f', 'Outros Recursos Vinculados à Saúde', 't', 'f', 9, 1, '', 'f', 0);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (266, 10, 'Recursos Vinculados  à Assistência Social', 0, 0, 0, 'f', 'f', 'f', 'f', 'f', 'Recursos Vinculados  à Assistência Social', 't', 'f', 10, 1, '', 'f', 0);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (266, 11, 'Recursos Vinculados ao RPPS - Fundo em Capitalização (Plano ', 0, 0, 0, 'f', 'f', 'f', 'f', 'f', 'Recursos Vinculados ao RPPS - Fundo em Capitalização (Plano Previdenciário)2', 't', 'f', 11, 1, '', 'f', 0);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (266, 12, 'Recursos Vinculados  ao RPPS - Fundo em Repartição (Plano Fi', 0, 0, 0, 'f', 'f', 'f', 'f', 'f', 'Recursos Vinculados  ao RPPS - Fundo em Repartição (Plano Financeiro)', 't', 'f', 12, 1, '', 'f', 0);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (266, 13, 'Recursos Vinculados ao RPPS - Taxa de Administração', 0, 0, 0, 'f', 'f', 'f', 'f', 'f', 'Recursos Vinculados ao RPPS - Taxa de Administração', 't', 'f', 13, 1, '', 'f', 0);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (266, 14, 'Recursos de Operações de Crédito (exceto vinculados à Educaç', 0, 0, 0, 'f', 'f', 'f', 'f', 'f', 'Recursos de Operações de Crédito (exceto vinculados à Educação e à Saúde)', 't', 'f', 14, 1, '', 'f', 0);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (266, 15, 'Recursos de Alienação de Bens/Ativos', 0, 0, 0, 'f', 'f', 'f', 'f', 'f', 'Recursos de Alienação de Bens/Ativos', 't', 'f', 15, 1, '', 'f', 0);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (266, 16, 'Recursos Extraorçamentários', 0, 0, 0, 'f', 'f', 'f', 'f', 'f', 'Recursos Extraorçamentários', 't', 'f', 16, 1, '', 'f', 0);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (266, 17, 'Outros Recursos Vinculados', 0, 0, 0, 'f', 'f', 'f', 'f', 'f', 'Outros Recursos Vinculados', 't', 'f', 17, 1, '', 'f', 0);
insert into orcparamseq (o69_codparamrel, o69_codseq, o69_descr, o69_grupo, o69_grupoexclusao, o69_nivel, o69_libnivel, o69_librec, o69_libsubfunc, o69_libfunc, o69_verificaano, o69_labelrel, o69_manual, o69_totalizador, o69_ordem, o69_nivellinha, o69_observacao, o69_desdobrarlinha, o69_origem) values (266, 18, 'TOTAL (III) = (I + II)', 0, 0, 0, 'f', 'f', 'f', 'f', 'f', 'TOTAL (III) = (I + II)', 'f', 't', 18, 1, '', 'f', 0);
insert into orcparamseqfiltropadrao (o132_sequencial, o132_orcparamrel, o132_orcparamseq, o132_anousu, o132_filtro) values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 266, 2, 2022, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas/>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="" id="recurso"/>
 <fonterecurso operador="in" valor="0001" id="fonterecurso"/>
 <complemento operador="in" valor="" id="complemento"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>
');
insert into orcparamseqfiltropadrao (o132_sequencial, o132_orcparamrel, o132_orcparamseq, o132_anousu, o132_filtro) values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 266, 3, 2022, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas/>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="" id="recurso"/>
 <fonterecurso operador="in" valor="" id="fonterecurso"/>
 <complemento operador="in" valor="" id="complemento"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>
');
insert into orcparamseqfiltropadrao (o132_sequencial, o132_orcparamrel, o132_orcparamseq, o132_anousu, o132_filtro) values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 266, 5, 2022, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas/>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="" id="recurso"/>
 <fonterecurso operador="in" valor="0020" id="fonterecurso"/>
 <complemento operador="in" valor="" id="complemento"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>
');
insert into orcparamseqfiltropadrao (o132_sequencial, o132_orcparamrel, o132_orcparamseq, o132_anousu, o132_filtro) values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 266, 6, 2022, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas/>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="" id="recurso"/>
 <fonterecurso operador="in" valor="0031" id="fonterecurso"/>
 <complemento operador="in" valor="" id="complemento"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>
');
insert into orcparamseqfiltropadrao (o132_sequencial, o132_orcparamrel, o132_orcparamseq, o132_anousu, o132_filtro) values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 266, 7, 2022, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas/>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="" id="recurso"/>
 <fonterecurso operador="in" valor="" id="fonterecurso"/>
 <complemento operador="in" valor="" id="complemento"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>
');
insert into orcparamseqfiltropadrao (o132_sequencial, o132_orcparamrel, o132_orcparamseq, o132_anousu, o132_filtro) values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 266, 8, 2022, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas/>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="" id="recurso"/>
 <fonterecurso operador="in" valor="0040" id="fonterecurso"/>
 <complemento operador="in" valor="" id="complemento"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>
');
insert into orcparamseqfiltropadrao (o132_sequencial, o132_orcparamrel, o132_orcparamseq, o132_anousu, o132_filtro) values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 266, 9, 2022, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas/>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="" id="recurso"/>
 <fonterecurso operador="in" valor="" id="fonterecurso"/>
 <complemento operador="in" valor="" id="complemento"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>
');
insert into orcparamseqfiltropadrao (o132_sequencial, o132_orcparamrel, o132_orcparamseq, o132_anousu, o132_filtro) values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 266, 10, 2022, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas/>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="" id="recurso"/>
 <fonterecurso operador="in" valor="" id="fonterecurso"/>
 <complemento operador="in" valor="" id="complemento"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>
');
insert into orcparamseqfiltropadrao (o132_sequencial, o132_orcparamrel, o132_orcparamseq, o132_anousu, o132_filtro) values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 266, 11, 2022, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas/>
 <orgao operador="in" valor="50" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="" id="recurso"/>
 <fonterecurso operador="in" valor="0400,0050" id="fonterecurso"/>
 <complemento operador="in" valor="" id="complemento"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>
');
insert into orcparamseqfiltropadrao (o132_sequencial, o132_orcparamrel, o132_orcparamseq, o132_anousu, o132_filtro) values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 266, 12, 2022, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas/>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="" id="recurso"/>
 <fonterecurso operador="in" valor="" id="fonterecurso"/>
 <complemento operador="in" valor="" id="complemento"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>
');
insert into orcparamseqfiltropadrao (o132_sequencial, o132_orcparamrel, o132_orcparamseq, o132_anousu, o132_filtro) values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 266, 13, 2022, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas/>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="" id="recurso"/>
 <fonterecurso operador="in" valor="" id="fonterecurso"/>
 <complemento operador="in" valor="" id="complemento"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>
');
insert into orcparamseqfiltropadrao (o132_sequencial, o132_orcparamrel, o132_orcparamseq, o132_anousu, o132_filtro) values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 266, 14, 2022, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas/>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="" id="recurso"/>
 <fonterecurso operador="in" valor="" id="fonterecurso"/>
 <complemento operador="in" valor="" id="complemento"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>
');
insert into orcparamseqfiltropadrao (o132_sequencial, o132_orcparamrel, o132_orcparamseq, o132_anousu, o132_filtro) values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 266, 15, 2022, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas/>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="" id="recurso"/>
 <fonterecurso operador="in" valor="" id="fonterecurso"/>
 <complemento operador="in" valor="" id="complemento"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>
');
insert into orcparamseqfiltropadrao (o132_sequencial, o132_orcparamrel, o132_orcparamseq, o132_anousu, o132_filtro) values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 266, 16, 2022, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas/>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="" id="recurso"/>
 <fonterecurso operador="in" valor="8001,8002,8003" id="fonterecurso"/>
 <complemento operador="in" valor="" id="complemento"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>
');
insert into orcparamseqfiltropadrao (o132_sequencial, o132_orcparamrel, o132_orcparamseq, o132_anousu, o132_filtro) values (nextval('orcparamelementospadrao_o132_sequencial_seq'), 266, 17, 2022, '<?xml version="1.0" encoding="ISO-8859-1"?>
<filter>
 <contas/>
 <orgao operador="in" valor="" id="orgao"/>
 <unidade operador="in" valor="" id="unidade"/>
 <funcao operador="in" valor="" id="funcao"/>
 <subfuncao operador="in" valor="" id="subfuncao"/>
 <programa operador="in" valor="" id="programa"/>
 <projativ operador="in" valor="" id="projativ"/>
 <recurso operador="in" valor="" id="recurso"/>
 <fonterecurso operador="in" valor="" id="fonterecurso"/>
 <complemento operador="in" valor="" id="complemento"/>
 <recursocontalinha numerolinha="" id="recursocontalinha"/>
 <observacao valor=""/>
 <desdobrarlinha valor="false"/>
</filter>
');
insert into orcparamseqcoluna (o115_sequencial, o115_anousu, o115_descricao, o115_tipo, o115_valoresdefault, o115_nomecoluna, o115_formula, o115_origem, o115_relatorio) values (nextval('orcparamseqcoluna_o115_sequencial_seq'), 2015, 'DEMAIS OBRIGAÇÕES FINANCEIRAS', 1, '', 'financeira', '', 0, 266);
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 18, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 5, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 17, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 5, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 16, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 5, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 15, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 5, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 14, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 5, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 13, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 5, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 5, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 5, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 5, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 5, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 5, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 5, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 5, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 5, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 5, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 5, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 5, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 5, 13, '');
insert into orcparamseqcoluna (o115_sequencial, o115_anousu, o115_descricao, o115_tipo, o115_valoresdefault, o115_nomecoluna, o115_formula, o115_origem, o115_relatorio) values (nextval('orcparamseqcoluna_o115_sequencial_seq'), 2017, 'INSUFICIÊNCIA FINANCEIRA', 0, '', 'insuficiencia_financeira', '', 0, 266);
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 18, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 6, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 17, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 6, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 16, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 6, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 15, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 6, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 14, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 6, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 13, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 6, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 6, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 6, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 6, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 6, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 6, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 6, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 6, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 6, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 6, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 6, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 6, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 6, 13, '');
insert into orcparamseqcoluna (o115_sequencial, o115_anousu, o115_descricao, o115_tipo, o115_valoresdefault, o115_nomecoluna, o115_formula, o115_origem, o115_relatorio) values (nextval('orcparamseqcoluna_o115_sequencial_seq'), 2022, 'RP - De Exercícios Anteriores (b)', 1, '', 'saldo_liquidados', '', 0, 266);
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 18, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 2, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 17, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 2, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 16, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 2, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 15, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 2, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 14, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 2, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 13, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 2, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 2, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 2, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 2, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 2, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 2, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 2, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 2, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 2, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 2, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 2, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 2, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 2, 13, '');
insert into orcparamseqcoluna (o115_sequencial, o115_anousu, o115_descricao, o115_tipo, o115_valoresdefault, o115_nomecoluna, o115_formula, o115_origem, o115_relatorio) values (nextval('orcparamseqcoluna_o115_sequencial_seq'), 2022, 'EMPENHOS NÃO LIQUIDADOS CANCELADOS (NÃO INSCRITOS POR INSUFICIÊNCIA FINANCEIRA)', 1, '', 'anulado_acumulado', '', 0, 266);
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 18, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 9, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 17, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 9, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 16, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 9, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 15, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 9, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 14, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 9, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 13, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 9, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 9, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 9, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 9, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 9, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 9, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 9, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 9, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 9, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 9, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 9, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 9, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 9, 13, '');
insert into orcparamseqcoluna (o115_sequencial, o115_anousu, o115_descricao, o115_tipo, o115_valoresdefault, o115_nomecoluna, o115_formula, o115_origem, o115_relatorio) values (nextval('orcparamseqcoluna_o115_sequencial_seq'), 2017, 'DISPONIBILIDADE DE CAIXA LIQUIDA', 0, '', 'disp_caixa_liquida', '', 0, 266);
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 18, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 7, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 17, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 7, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 16, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 7, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 15, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 7, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 14, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 7, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 13, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 7, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 7, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 7, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 7, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 7, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 7, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 7, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 7, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 7, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 7, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 7, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 7, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 7, 13, '');
insert into orcparamseqcoluna (o115_sequencial, o115_anousu, o115_descricao, o115_tipo, o115_valoresdefault, o115_nomecoluna, o115_formula, o115_origem, o115_relatorio) values (nextval('orcparamseqcoluna_o115_sequencial_seq'), 2015, 'DISPONIBILIDADE DE CAIXA LÍQUIDA APÓS A INSCRIÇÃO', 1, '', 'disp_caixa', '', 0, 266);
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 18, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 10, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 17, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 10, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 16, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 10, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 15, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 10, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 14, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 10, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 13, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 10, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 10, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 10, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 10, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 10, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 10, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 10, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 10, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 10, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 10, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 10, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 10, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 10, 13, '');
insert into orcparamseqcoluna (o115_sequencial, o115_anousu, o115_descricao, o115_tipo, o115_valoresdefault, o115_nomecoluna, o115_formula, o115_origem, o115_relatorio) values (nextval('orcparamseqcoluna_o115_sequencial_seq'), 2022, 'Restos a Pagar Empenhados e Não Liquidados de Exercícios Anteriores (d)', 1, '', 'saldo_a_liquidar', '', 0, 266);
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 4, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 14, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 4, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 15, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 4, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 16, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 4, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 17, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 4, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 18, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 4, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 4, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 4, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 4, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 4, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 4, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 4, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 4, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 4, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 4, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 4, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 13, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 4, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 4, 13, '');
insert into orcparamseqcoluna (o115_sequencial, o115_anousu, o115_descricao, o115_tipo, o115_valoresdefault, o115_nomecoluna, o115_formula, o115_origem, o115_relatorio) values (nextval('orcparamseqcoluna_o115_sequencial_seq'), 2022, 'RP - Do Exercício (c)', 1, '', 'a_pagar_liquidado', '', 0, 266);
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 18, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 3, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 17, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 3, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 16, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 3, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 15, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 3, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 14, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 3, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 13, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 3, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 3, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 3, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 3, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 3, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 3, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 3, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 3, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 3, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 3, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 3, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 3, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 3, 13, '');
insert into orcparamseqcoluna (o115_sequencial, o115_anousu, o115_descricao, o115_tipo, o115_valoresdefault, o115_nomecoluna, o115_formula, o115_origem, o115_relatorio) values (nextval('orcparamseqcoluna_o115_sequencial_seq'), 2021, 'DISPONIBILIDADE DE CAIXA BRUTA (a)', 1, '', 'saldo_final_acumulado', '', 0, 266);
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 18, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 17, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 16, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 15, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 14, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 13, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 1, 13, '');
insert into orcparamseqcoluna (o115_sequencial, o115_anousu, o115_descricao, o115_tipo, o115_valoresdefault, o115_nomecoluna, o115_formula, o115_origem, o115_relatorio) values (nextval('orcparamseqcoluna_o115_sequencial_seq'), 2021, 'RESTOS A PAGAR EMPENHADOS E NÃO LIQUIDADOS DO EXERCÍCIO (h)', 1, '', 'a_liquidar', '', 0, 266);
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 2, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 8, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 14, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 8, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 15, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 8, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 16, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 8, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 17, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 8, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 18, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 8, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 3, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 8, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 4, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 8, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 5, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 8, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 6, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 8, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 7, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 8, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 8, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 8, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 9, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 8, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 10, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 8, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 11, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 8, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 12, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 8, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 13, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 8, 13, '');
insert into orcparamseqorcparamseqcoluna (o116_sequencial, o116_codseq, o116_codparamrel, o116_orcparamseqcoluna, o116_ordem, o116_periodo, o116_formula) values (nextval('orcparamseqorcparamseqcoluna_o116_sequencial_seq'), 1, 266, currval('orcparamseqcoluna_o115_sequencial_seq'), 8, 13, '');
SQL
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::connection()->getPdo()->exec(<<<SQL
delete from orcparamseqfiltroorcamento where o133_orcparamrel = 266;
delete from orcparamrelperiodos where o113_orcparamrel = 266;
delete from orcparamseqfiltropadrao where o132_orcparamrel = 266;
delete from orcparamseqorcparamseqcolunavalor where o117_orcparamseqorcparamseqcoluna in (
    select o116_sequencial from orcparamseqorcparamseqcoluna where o116_codparamrel = 266
);
delete from orcparamseqorcparamseqcoluna where o116_codparamrel = 266;
delete from orcparamseqorcparamseqcoluna where o116_codparamrel = 266;
delete from orcparamseq where o69_codparamrel = 266;
delete from orcparamrel where o42_codparrel = 266;
SQL
        );
    }
}
