<?php
/**
 *     E-cidade Software Publico para Gestao Municipal
 *  Copyright (C) 2009  DBSeller Servicos de Informatica
 *                            www.dbseller.com.br
 *                         e-cidade@dbseller.com.br
 *
 *  Este programa e software livre; voce pode redistribui-lo e/ou
 *  modifica-lo sob os termos da Licenca Publica Geral GNU, conforme
 *  publicada pela Free Software Foundation; tanto a versao 2 da
 *  Licenca como (a seu criterio) qualquer versao mais nova.
 *
 *  Este programa e distribuido na expectativa de ser util, mas SEM
 *  QUALQUER GARANTIA; sem mesmo a garantia implicita de
 *  COMERCIALIZACAO ou de ADEQUACAO A QUALQUER PROPOSITO EM
 *  PARTICULAR. Consulte a Licenca Publica Geral GNU para obter mais
 *  detalhes.
 *
 *  Voce deve ter recebido uma copia da Licenca Publica Geral GNU
 *  junto com este programa; se nao, escreva para a Free Software
 *  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA
 *  02111-1307, USA.
 *
 *  Copia da licenca no diretorio licenca/licenca_en.txt
 *                                licenca/licenca_pt.txt
 */

namespace ECidade\Tributario\Juridico\Parametro\Repository;

use BusinessException;

class ParametroRepository extends \BaseClassRepository
{
    public static function getParametroAtual($codigoInstituicao, $exercicio)
    {
        $sql = "
            select 
                v19_partilha as partilha 
            from 
                parjuridico 
            where 
                v19_instit = {$codigoInstituicao} 
                and v19_anousu = {$exercicio}";
        $rs = db_query($sql);
        if (!$rs) {
            $mensagem = "Houve um erro ao buscar os parâmetros do módulo juridico para a instituição com código ";
            $mensagem .= "{$codigoInstituicao}.";
            throw new \DBException($mensagem);
        }
        if (pg_num_rows($rs) == 0) {
            $mensagem = "Nenhum parâmetro encontrado do módulo juridico para a instituição com código ";
            $mensagem .= "{$codigoInstituicao}.";
            throw new \Businessxception($mensagem);
        }
        $parametro = \db_utils::fieldsMemory($rs, 0);
        return $parametro;
    }
}
