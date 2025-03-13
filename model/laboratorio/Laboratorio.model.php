<?php

/**
 * Class Laboratorio
 * @packge laboratorio
 * @author Andrio Costa <andrio.costa@dbseller.com.br>
 * @version $Revision: 1.4 $
 */
class Laboratorio
{
    private $iCodigo;

    private $iTipo;

    private $sLaboratorio;

    private $iAlvara;

    private $iCnes;

    private $sEndereco;

    private $iTelefone;

    private $sNumero;

    private $iTurnoAtendimento;

    private $isInterfaceado;

    public function __construct($iCodigo = null)
    {
        if (!empty($iCodigo)) {
            $oDaoLaboratorio = new cl_lab_laboratorio();
            $sSqlLaboratorio = $oDaoLaboratorio->sql_query_file($iCodigo);
            $rsLaboratorio = db_query($sSqlLaboratorio);

            if ($rsLaboratorio && pg_num_rows($rsLaboratorio) == 0) {
                return false;
            }
            $oDadosLaboratorio = db_utils::fieldsMemory($rsLaboratorio, 0);
            $this->iCodigo = $oDadosLaboratorio->la02_i_codigo;
            $this->iTipo = $oDadosLaboratorio->la02_i_tipo;
            $this->sLaboratorio = $oDadosLaboratorio->la02_c_descr;
            $this->iAlvara = $oDadosLaboratorio->la02_i_alvara;
            $this->iCnes = $oDadosLaboratorio->la02_i_cnes;
            $this->sEndereco = $oDadosLaboratorio->la02_c_endereco;
            $this->iTelefone = $oDadosLaboratorio->la02_i_telefone;
            $this->sNumero = $oDadosLaboratorio->la02_c_numero;
            $this->iTurnoAtendimento = $oDadosLaboratorio->la02_i_turnoatend;
            $this->isInterfaceado = $oDadosLaboratorio->la02_interfaceado === 't';
        }
        return true;
    }

    /**
     * Valida se o departamento informado esta vinculádo a um laboratório
     *
     * @param DBDepartamento $oDepartamento
     * @return bool
     */
    public static function departamentoIsLaboratorio(DBDepartamento $oDepartamento)
    {
        $sWhere = " la03_i_departamento = {$oDepartamento->getCodigo()} ";
        $oDaoLabDepart = new cl_lab_labdepart();
        $sSqlDepart = $oDaoLabDepart->sql_query_file(null, "1", null, $sWhere);
        $rsDepart = db_query($sSqlDepart);

        if ($rsDepart && pg_num_rows($rsDepart) == 0) {
            return false;
        }
        return true;
    }

    /**
     * Validamos se o Usuário informado, esta vinculado ao laboratório
     * @param DBDepartamento $oDepartamento
     * @param UsuarioSistema $oUsuario
     * @return bool
     */
    public static function usuarioIsTecnicoLaboratorio(DBDepartamento $oDepartamento, UsuarioSistema $oUsuario)
    {
        $sWhere = " id_usuario = {$oUsuario->getIdUsuario()} and la03_i_departamento = {$oDepartamento->getCodigo()} ";
        $oDaoLabResp = new cl_lab_labresp();
        $sSqlLabResp = $oDaoLabResp->sql_query_responsavel(null, "1", null, $sWhere);
        $rsLabResp = db_query($sSqlLabResp);

        if ($rsLabResp && pg_num_rows($rsLabResp) == 0) {
            return false;
        }
        return true;
    }

    public static function getLaboratorioByDepartamento(DBDepartamento $oDepartamento)
    {
        $sWhere = " la03_i_departamento = {$oDepartamento->getCodigo()} ";
        $oDaoLabDepart = new cl_lab_labdepart();
        $sSqlDepart = $oDaoLabDepart->sql_query_file(null, "la03_i_laboratorio", null, $sWhere);
        $rsDepart = db_query($sSqlDepart);

        if ($rsDepart && pg_num_rows($rsDepart) == 0) {
            return false;
        }

        return new Laboratorio(db_utils::fieldsMemory($rsDepart, 0)->la03_i_laboratorio);
    }

    /**
     * retorna o código do laboratório
     * @return integer
     */
    public function getCodigo()
    {
        return $this->iCodigo;
    }

    public function setTipo($iTipo)
    {
        $this->iTipo = $iTipo;
        return $this;
    }

    public function getTipo()
    {
        return $this->iTipo;
    }

    /**
     * define o nome do Laboratório
     * @param string $sLaboratorio
     * @return Laboratorio
     */
    public function setDescricao($sLaboratorio)
    {
        $this->sLaboratorio = $sLaboratorio;
        return $this;
    }

    /**
     * Retorna o nome do laboratório
     * @return string
     */
    public function getDescricao()
    {
        return $this->sLaboratorio;
    }

    /**
     * @param integer $iAlvara
     * @return Laboratorio
     */
    public function setAlvara($iAlvara)
    {
        $this->iAlvara = $iAlvara;
        return $this;
    }

    /**
     * @return integer
     */
    public function getAlvara()
    {
        return $this->iAlvara;
    }

    /**
     * @param integer $iCnes
     * @return Laboratorio
     */
    public function setCnes($iCnes)
    {
        $this->iCnes = $iCnes;
        return $this;
    }

    /**
     * @return integer
     */
    public function getCnes()
    {
        return $this->iCnes;
    }

    /**
     * @param string $sEndereco
     * @return Laboratorio
     */
    public function setEndereco($sEndereco)
    {
        $this->sEndereco = $sEndereco;
        return $this;
    }

    /**
     * @return string
     */
    public function getEndereco()
    {
        return $this->sEndereco;
    }

    /**
     * @return integer
     */
    public function getTelefone()
    {
        return $this->iTelefone;
    }

    /**
     * @param integer $iTelefone
     * @return Laboratorio
     */
    public function setTelefone($iTelefone)
    {
        $this->iTelefone = $iTelefone;
        return $this;
    }

    /**
     * @param string $sNumero
     * @return Laboratorio
     */
    public function setNumero($sNumero)
    {
        $this->sNumero = $sNumero;
        return $this;
    }

    /**
     * @return string
     */
    public function getNumero()
    {
        return $this->sNumero;
    }

    /**
     * @param integer $iTurnoAtendimento
     * @return Laboratorio
     */
    public function setTurnoAtendimento($iTurnoAtendimento)
    {
        $this->iTurnoAtendimento = $iTurnoAtendimento;
        return $this;
    }

    /**
     * @return integer
     */
    public function getTurnoAtendimento()
    {
        return $this->iTurnoAtendimento;
    }

    /**
     * @param bool $isInterfaceado
     * @return Laboratorio
     */
    public function setIsInterfaceado($isInterfaceado)
    {
        $this->isInterfaceado = $isInterfaceado;
        return $this;
    }

    /**
     * @return bool
     */
    public function isInterfaceado()
    {
        return $this->isInterfaceado;
    }
}
