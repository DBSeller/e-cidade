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

/**
 * Class cl_esocialrubricas
 * @property $eso26_sequencial
 * @property $eso26_rubrica
 * @property $eso26_instituicao
 * @property $eso26_avaliacaoperguntaopcaocodinccp
 * @property $eso26_avaliacaoperguntaopcaocodincirrf
 * @property $eso26_avaliacaoperguntaopcaocodincfgts
 * @property $eso26_avaliacaoperguntaopcaocodincsind
 * @property $eso26_natureza
 * @property $eso26_datainicial
 * @property $eso26_datafinal
 * @property $eso26_avaliacaoperguntaopcaocodinccprp
 * @property $eso26_avaliacaoperguntaopcaocodtetoremun
 * @property $eso26_subgrupotce
 */
class cl_esocialrubricas extends DAOBasica
{
    /**
     * cl_esocialrubricas constructor.
     */
    public function __construct()
    {
        parent::__construct('esocial.esocialrubricas');
    }
}
