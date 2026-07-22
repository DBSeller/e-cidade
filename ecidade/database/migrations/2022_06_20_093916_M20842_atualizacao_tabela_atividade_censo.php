<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class M20842AtualizacaoTabelaAtividadeCenso extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
insert into escola.censoativcompl
values (19001, 19, 'Eventos de celebração à Diversidade Cultural na Escola (Feira das Nações, Feira dos Estados, etc.)', true),
       (19002, 19, 'Promoção do respeito à Diversidade Cultural', true),
       (22033, 22, 'Balé', true),
       (31016, 31, 'Linguagens', true),
       (31017, 31, 'Ciências da Natureza', true),
       (31018, 31, 'Ciências Humanas e Sociais.', true),
       (41016, 41, 'Estudo do Estatuto do Idoso', true),
       (41017, 41, 'Legislação e conduta no Trânsito', true),
       (41018, 41, 'Parcerias com os órgãos de Trânsito', true),
       (41019, 41, 'Ações de respeito à diversidade', true),
       (41020, 41, 'Constituição, direitos e deveres do cidadão', true),
       (41021, 41, 'Estudo do Estatuto da Criança e do Adolescente', true),
       (41022, 41, 'Ações de integração Família e Escola', true),
       (41023, 41, 'Ações de integração Comunidade e Escola', true),
       (41024, 41, 'Vida Familiar e Social', true),
       (13306, 13, 'Escolas sustentáveis e COM-vida', true),
       (13307, 13, 'Coleta seletiva/ Gestão de resíduos', true),
       (13308, 13, 'Captação e aproveitamento de Água de Chuva', true),
       (13309, 13, 'Uso de energias alternativas na escola', true),
       (13310, 13, 'Projetos de pesquisa na escola e entorno', true),
       (15401, 15, 'Respeito à Diversidade Étnico-Racial', true),
       (15402, 15, 'A contribuição dos povos no Multiculturalismo Brasileiro', true),
       (17401, 17, 'Direitos e Deveres do Trabalhador', true),
       (17402, 17, 'O mundo do trabalho', true),
       (19101, 19, 'Promoção da Saúde', true),
       (19102, 19, 'Higiene e Cuidados Pessoais/ Higiene Pessoal', true),
       (19103, 19, 'Saúde Bucal', true),
       (19104, 19, 'Campanhas de vacinação', true),
       (19105, 19, 'Educação em saúde reprodutiva', true),
       (19106, 19, 'Prevenção ao uso de Álcool, Tabaco e Drogas', true),
       (19107, 19, 'Primeiros Socorros', true),
       (19108, 19, 'Ações de prevenção a doenças epidemiológicas', true),
       (19109, 19, 'Meditação', true),
       (19201, 19, 'Desenvolvimento de competências socioemocionais', true),
       (19202, 19, 'Atividades de autoconhecimento, identificação e gestão de sentimento', true),
       (19203, 19, 'Atividades de empatia e gestão de conflitos', true),
       (20101, 20, 'Educação alimentar e nutricional', true),
       (20102, 20, 'Estudos dos aspectos nutricionais dos alimentos', true),
       (20103, 20, 'Ações de Prevenção dos distúrbios alimentares', true),
       (20104, 20, 'Elaboração de Cardápio Contextualizado local', true);

update escola.censoativcompl set ed133_c_descr = 'Fomento da Economia Solidária e Criativa' where ed133_i_codigo = 17102;
update escola.censoativcompl set ed133_ativo = false where ed133_i_codigo = 31002;
update escola.censoativcompl set ed133_ativo = false where ed133_i_codigo = 71007;
update escola.censoativcompl set ed133_ativo = false where ed133_i_codigo = 13302;
update escola.censoativcompl set ed133_ativo = false where ed133_i_codigo = 18101;
update escola.censoativcompl set ed133_ativo = false where ed133_i_codigo = 18102;
update escola.censoativcompl set ed133_ativo = false where ed133_i_codigo = 18103;

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
delete from escola.censoativcompl where ed133_i_codigo in (19001, 19002, 22033, 31016, 31017, 31018, 41016, 41017, 41018, 41019, 41020, 41021, 41022, 41023, 41024, 13306, 13307, 13308, 13309, 13310, 15401, 15402, 17401, 17402, 19101, 19102, 19103, 19104, 19105, 19106, 19107, 19108, 19109, 19201, 19202, 19203, 20101, 20102, 20103, 20104);

update escola.censoativcompl set ed133_c_descr = 'Economia Solidária e Criativa' where ed133_i_codigo = 17102;
update escola.censoativcompl set ed133_ativo = true where ed133_i_codigo = 31002;
update escola.censoativcompl set ed133_ativo = true where ed133_i_codigo = 71007;
update escola.censoativcompl set ed133_ativo = true where ed133_i_codigo = 13302;
update escola.censoativcompl set ed133_ativo = true where ed133_i_codigo = 18101;
update escola.censoativcompl set ed133_ativo = true where ed133_i_codigo = 18102;
update escola.censoativcompl set ed133_ativo = true where ed133_i_codigo = 18103;

SQL
        );
    }
}
