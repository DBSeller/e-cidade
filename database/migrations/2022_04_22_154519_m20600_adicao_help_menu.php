<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class M20600AdicaoHelpMenu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::connection()->getPdo()->exec(<<<SQL
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!cadastros_categoria_cnh.md' where id_item = 5352;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!cadastros_categoria_cnh.md' where id_item = 5353;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!cadastros_categoria_cnh.md' where id_item = 5354;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!cadastros_categorias.md' where id_item = 5417;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!cadastros_categorias.md' where id_item = 5418;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!cadastros_categorias.md' where id_item = 5419;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!cadastros_central_de_veiculos.md' where id_item = 1050934;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!cadastros_central_de_veiculos.md' where id_item = 1056456;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!cadastros_central_de_veiculos.md' where id_item = 1061979;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!cadastros_combustiveis.md' where id_item = 5364;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!cadastros_combustiveis.md' where id_item = 5365;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!cadastros_combustiveis.md' where id_item = 5366;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!cadastros_convenios_uso_veicular.md' where id_item = 853386;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!cadastros_convenios_uso_veicular.md' where id_item = 858855;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!cadastros_convenios_uso_veicular.md' where id_item = 864325;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!cadastros_cores.md' where id_item = 5376;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!cadastros_cores.md' where id_item = 5377;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!cadastros_cores.md' where id_item = 5378;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!cadastros_itens_obrigatorios.md' where id_item = 722494;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!cadastros_itens_obrigatorios.md' where id_item = 727935;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!cadastros_itens_obrigatorios.md' where id_item = 733377;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!cadastros_marcas.md' where id_item = 5344;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!cadastros_marcas.md' where id_item = 5345;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!cadastros_marcas.md' where id_item = 5346;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!cadastros_modelo.md' where id_item = 5356;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!cadastros_modelo.md' where id_item = 5358;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!cadastros_modelo.md' where id_item = 5397;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!cadastros_motoristas.md' where id_item = 5392;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!cadastros_motoristas.md' where id_item = 5393;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!cadastros_motoristas.md' where id_item = 5394;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!cadastros_oficinas.md' where id_item = 5368;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!cadastros_oficinas.md' where id_item = 5369;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!cadastros_oficinas.md' where id_item = 5370;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!cadastros_postos.md' where id_item = 5380;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!cadastros_postos.md' where id_item = 5381;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!cadastros_postos.md' where id_item = 5382;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!cadastros_potencias.md' where id_item = 5413;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!cadastros_potencias.md' where id_item = 5414;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!cadastros_potencias.md' where id_item = 5415;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!cadastros_procedencias.md' where id_item = 5409;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!cadastros_procedencias.md' where id_item = 5410;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!cadastros_procedencias.md' where id_item = 5411;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!cadastros_situacao_de_condutores.md' where id_item = 5421;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!cadastros_situacao_de_condutores.md' where id_item = 5422;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!cadastros_situacao_de_condutores.md' where id_item = 5423;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!cadastros_tipo_de_baixa_itens.md' where id_item = 744264;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!cadastros_tipo_de_baixa_itens.md' where id_item = 749709;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!cadastros_tipo_de_baixa_itens.md' where id_item = 755155;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!cadastros_tipos_de_abastecimento.md' where id_item = 679006;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!cadastros_tipos_de_abastecimento.md' where id_item = 684437;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!cadastros_tipos_de_abastecimento.md' where id_item = 689869;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!cadastros_tipos_de_baixa_veiculos.md' where id_item = 700740;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!cadastros_tipos_de_baixa_veiculos.md' where id_item = 706177;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!cadastros_tipos_de_baixa_veiculos.md' where id_item = 711615;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!cadastros_tipos_de_capacidade.md' where id_item = 5342;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!cadastros_tipos_de_capacidade.md' where id_item = 5357;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!cadastros_tipos_de_capacidade.md' where id_item = 5360;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!cadastros_tipos_de_servico.md' where id_item = 5372;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!cadastros_tipos_de_servico.md' where id_item = 5373;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!cadastros_tipos_de_servico.md' where id_item = 5374;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!cadastros_tipos.md' where id_item = 5348;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!cadastros_tipos.md' where id_item = 5349;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!cadastros_tipos.md' where id_item = 5350;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!cadastros_utilizacao_de_veiculos.md' where id_item = 831520;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!cadastros_utilizacao_de_veiculos.md' where id_item = 836985;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!cadastros_utilizacao_de_veiculos.md' where id_item = 842451;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!cadastros_veiculos.md' where id_item = 5388;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!cadastros_veiculos.md' where id_item = 5389;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!cadastros_veiculos.md' where id_item = 5390;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!consultas_veiculos.md' where id_item = 5456;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!procedimentos_abastecimento.md' where id_item = 5431;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!procedimentos_abastecimento.md' where id_item = 5432;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!procedimentos_abastecimento.md' where id_item = 5433;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!procedimentos_autorizacao_de_circulacao.md' where id_item = 10137;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!procedimentos_autorizacao_de_circulacao.md' where id_item = 10138;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!procedimentos_baixa_de_itens_obrigatorios.md' where id_item = 766050;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!procedimentos_baixa_de_itens_obrigatorios.md' where id_item = 771499;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!procedimentos_baixa_de_itens_obrigatorios.md' where id_item = 776949;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!procedimentos_baixa_de_veiculos.md' where id_item = 5396;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!procedimentos_baixa_de_veiculos.md' where id_item = 5398;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!procedimentos_devolucao_de_veiculos.md' where id_item = 5405;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!procedimentos_devolucao_de_veiculos.md' where id_item = 5406;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!procedimentos_devolucao_de_veiculos.md' where id_item = 5407;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!procedimentos_manutencao_de_veiculos.md' where id_item = 5425;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!procedimentos_manutencao_de_veiculos.md' where id_item = 5426;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!procedimentos_manutencao_de_veiculos.md' where id_item = 5427;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!procedimentos_manutencao_medidas.md' where id_item = 8937;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!procedimentos_manutencao_medidas.md' where id_item = 8934;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!procedimentos_manutencao_parametros.md' where id_item = 5386;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!procedimentos_retirada_de_veiculos.md' where id_item = 5401;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!procedimentos_retirada_de_veiculos.md' where id_item = 5402;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!procedimentos_retirada_de_veiculos.md' where id_item = 5403;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!relatorios_abastecimento.md' where id_item = 608583;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!relatorios_categoria_cnh.md' where id_item = 5451;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!relatorios_categorias.md' where id_item = 5452;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!relatorios_central_de_veiculos.md' where id_item = 6977;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!relatorios_combustiveis.md' where id_item = 5450;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!relatorios_controle_de_hodometro.md' where id_item = 10152;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!relatorios_cores.md' where id_item = 5449;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!relatorios_ficha_controle_manutencao.md' where id_item = 10149;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!relatorios_manutencao.md' where id_item = 10144;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!relatorios_marcas.md' where id_item = 5448;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!relatorios_modelos.md' where id_item = 5447;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!relatorios_motoristas.md' where id_item = 5438;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!relatorios_oficinas.md' where id_item = 5445;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!relatorios_ordem_de_servico.md' where id_item = 10135;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!relatorios_postos.md' where id_item = 5444;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!relatorios_potencias.md' where id_item = 5443;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!relatorios_procedencias.md' where id_item = 5442;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!relatorios_situacao_de_condutores.md' where id_item = 5446;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!relatorios_tipo_de_capacidade.md' where id_item = 5440;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!relatorios_tipo_de_servicos.md' where id_item = 5439;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!relatorios_tipos.md' where id_item = 5441;
        update db_itensmenu set help = 'https://e-cidade.wiki.br/patrimonial/veiculos/#!relatorios_veiculos.md' where id_item = 5437;
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
        update db_itensmenu set help = 'Relatório do processamento do fechamento do estoque.' where id_item = 9869;
        update db_itensmenu set help = 'Exclusão de Departdiv' where id_item = 5155;
        update db_itensmenu set help = 'Relatório de central de veículos' where id_item = 6977;
        update db_itensmenu set help = 'Agendamento de Consulta Simplificado' where id_item = 7935;
        update db_itensmenu set help = 'Alteração de Veiccadmodelo' where id_item = 5357;
        update db_itensmenu set help = 'Alteração de Modelo' where id_item = 5397;
        update db_itensmenu set help = 'Emissão de relatório' where id_item = 9709;
        update db_itensmenu set help = 'Financeira ISSQN' where id_item = 2451;
        update db_itensmenu set help = 'Levantamento' where id_item = 2494;
        update db_itensmenu set help = 'Relatório de Postos' where id_item = 5444;
        update db_itensmenu set help = 'Relatório de Oficinas' where id_item = 5445;
        update db_itensmenu set help = 'Relatório de Modelos' where id_item = 5447;
        update db_itensmenu set help = 'Relatório de Marcas' where id_item = 5448;
        update db_itensmenu set help = 'Relatório de Cores' where id_item = 5449;
        update db_itensmenu set help = 'Relatório de Combustíveis' where id_item = 5450;
        update db_itensmenu set help = 'Relatório de Categoria CNH' where id_item = 5451;
        update db_itensmenu set help = 'Consulta Veículos' where id_item = 5456;
        update db_itensmenu set help = 'Relatório de Procedências' where id_item = 5442;
        update db_itensmenu set help = 'Relatório de Potências' where id_item = 5443;
        update db_itensmenu set help = 'Relatório de Categorias' where id_item = 5452;
        update db_itensmenu set help = 'Alteração de Veiccadcateg' where id_item = 5418;
        update db_itensmenu set help = 'Exclusão de Veiccadcateg' where id_item = 5419;
        update db_itensmenu set help = 'Alteração de Veicmanut' where id_item = 5426;
        update db_itensmenu set help = 'Inclusão de Veiccadmotoristasit' where id_item = 5421;
        update db_itensmenu set help = 'Alteração de Veiccadmotoristasit' where id_item = 5422;
        update db_itensmenu set help = 'Exclusão de Veiccadmotoristasit' where id_item = 5423;
        update db_itensmenu set help = 'Exclusão de Veicmanut' where id_item = 5427;
        update db_itensmenu set help = 'Inclusão de Veicabast' where id_item = 5431;
        update db_itensmenu set help = 'Alteração de Veicabast' where id_item = 5432;
        update db_itensmenu set help = 'Relatório de Veículos' where id_item = 5437;
        update db_itensmenu set help = 'Relatório de Motoristas' where id_item = 5438;
        update db_itensmenu set help = 'Relatório de Tipo de Serviço prestado por oficinas' where id_item = 5439;
        update db_itensmenu set help = 'Relatório de Tipo de Capacidade' where id_item = 5440;
        update db_itensmenu set help = 'Relatório de Tipo de Veiculo' where id_item = 5441;
        update db_itensmenu set help = 'Exclusão de Veiccadmarca' where id_item = 5346;
        update db_itensmenu set help = 'Inclusão de Veiccadtipo' where id_item = 5348;
        update db_itensmenu set help = 'Alteração de Veiccadtipo' where id_item = 5349;
        update db_itensmenu set help = 'Exclusão de Veiccadtipo' where id_item = 5350;
        update db_itensmenu set help = 'Inclusão de Veiccadcategcnh' where id_item = 5352;
        update db_itensmenu set help = 'Alteração de Veiccadcategcnh' where id_item = 5353;
        update db_itensmenu set help = 'Exclusão de Veiccadcategcnh' where id_item = 5354;
        update db_itensmenu set help = 'Inclusão de Veiccadmodelo' where id_item = 5356;
        update db_itensmenu set help = 'Exclusão de Veiccadcapacidade' where id_item = 5342;
        update db_itensmenu set help = 'Exclusão de Veiccadmodelo' where id_item = 5358;
        update db_itensmenu set help = 'Inclusão de Veiccadtipocapacidade' where id_item = 5360;
        update db_itensmenu set help = 'Inclusão de Veiccadcomb' where id_item = 5364;
        update db_itensmenu set help = 'Alteração de Veiccadcomb' where id_item = 5365;
        update db_itensmenu set help = 'Exclusão de Veiccadcomb' where id_item = 5366;
        update db_itensmenu set help = 'Inclusão de Veiccadoficinas' where id_item = 5368;
        update db_itensmenu set help = 'Alteração de Veiccadoficinas' where id_item = 5369;
        update db_itensmenu set help = 'Exclusão de Veiccadoficinas' where id_item = 5370;
        update db_itensmenu set help = 'Inclusão de Veiccadtiposervico' where id_item = 5372;
        update db_itensmenu set help = 'Alteração de Veiccadtiposervico' where id_item = 5373;
        update db_itensmenu set help = 'Exclusão de Veiccadtiposervico' where id_item = 5374;
        update db_itensmenu set help = 'Inclusão de Veiculos' where id_item = 5388;
        update db_itensmenu set help = 'Alteração de Veiculos' where id_item = 5389;
        update db_itensmenu set help = 'Exclusão de Veiculos' where id_item = 5390;
        update db_itensmenu set help = 'Inclusão de Veiccadcor' where id_item = 5376;
        update db_itensmenu set help = 'Alteração de Veiccadcor' where id_item = 5377;
        update db_itensmenu set help = 'Exclusão de Veiccadcor' where id_item = 5378;
        update db_itensmenu set help = 'Inclusão de Veiccadposto' where id_item = 5380;
        update db_itensmenu set help = 'Inclusão de Veicmotoristas' where id_item = 5392;
        update db_itensmenu set help = 'Alteração de Veicmotoristas' where id_item = 5393;
        update db_itensmenu set help = 'Exclusão de Veicmotoristas' where id_item = 5394;
        update db_itensmenu set help = 'Inclusão de Veicbaixa' where id_item = 5396;
        update db_itensmenu set help = 'Exclusão de Veicbaixa' where id_item = 5398;
        update db_itensmenu set help = 'Inclusão de Veicretirada' where id_item = 5401;
        update db_itensmenu set help = 'Alteração de Veicretirada' where id_item = 5402;
        update db_itensmenu set help = 'Exclusão de Veicretirada' where id_item = 5403;
        update db_itensmenu set help = 'Inclusão de Veicdevolucao' where id_item = 5405;
        update db_itensmenu set help = 'Alteração de Veicdevolucao' where id_item = 5406;
        update db_itensmenu set help = 'Exclusão de Veicdevolucao' where id_item = 5407;
        update db_itensmenu set help = 'Inclusão de Veiccadmarca' where id_item = 5344;
        update db_itensmenu set help = 'Alteração de Veiccadmarca' where id_item = 5345;
        update db_itensmenu set help = 'Funções' where id_item = 4437;
        update db_itensmenu set help = 'Balancete da Receita' where id_item = 3377;
        update db_itensmenu set help = 'Manutenção de Lançamentos Contábeis' where id_item = 3386;
        update db_itensmenu set help = 'Assinaturas - Secretario Fazenda' where id_item = 1615;
        update db_itensmenu set help = '' where id_item = 6949;
        update db_itensmenu set help = 'Alteração de Convenio' where id_item = 4264;
        update db_itensmenu set help = 'Inclusão de Veicmanutencaomedida' where id_item = 8934;
        update db_itensmenu set help = 'Cancela Manutenção de Medida' where id_item = 8937;
        update db_itensmenu set help = 'Quadro de Desenvolvimento' where id_item = 8583;
        update db_itensmenu set help = 'Inclusão de Veiccadproced' where id_item = 5409;
        update db_itensmenu set help = 'Alteração de Veiccadproced' where id_item = 5410;
        update db_itensmenu set help = 'Exclusão de Veiccadproced' where id_item = 5411;
        update db_itensmenu set help = 'Anulação de Abastecimento' where id_item = 5433;
        update db_itensmenu set help = 'Inclusão de Veiccadpotencia' where id_item = 5413;
        update db_itensmenu set help = 'Alteração de Veiccadpotencia' where id_item = 5414;
        update db_itensmenu set help = 'Exclusão de Veiccadpotencia' where id_item = 5415;
        update db_itensmenu set help = 'Inclusão de Veicmanut' where id_item = 5425;
        update db_itensmenu set help = 'Inclusão de Veiccadcateg' where id_item = 5417;
        update db_itensmenu set help = 'Alteração de Veiccadposto' where id_item = 5381;
        update db_itensmenu set help = 'Exclusão de Veiccadposto' where id_item = 5382;
        update db_itensmenu set help = 'Relatório de Situações possiveis para um motorista' where id_item = 5446;
        update db_itensmenu set help = 'Manutenção de Parâmetros' where id_item = 5386;
SQL
        );
    }
}
