<?php

namespace App\Domain\Educacao\Escola\Models;

use App\Domain\Configuracao\Departamento\Models\Departamento;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Escola
 * @package App\Domain\Educacao\Escola\Models
 * @property integer $ed18_i_codigo
 * @property integer $ed18_i_rua
 * @property integer $ed18_i_numero
 * @property string $ed18_c_compl
 * @property integer $ed18_i_bairro
 * @property string $ed18_c_nome
 * @property string $ed18_c_abrev
 * @property integer $ed18_c_mantenedora
 * @property integer $ed18_i_anoinicio
 * @property string $ed18_c_email
 * @property string $ed18_c_homepage
 * @property string $ed18_c_tipo
 * @property string $ed18_c_codigoinep
 * @property string $ed18_c_local
 * @property string $ed18_c_logo
 * @property string $ed18_c_cep
 * @property integer $ed18_i_funcionamento
 * @property integer $ed18_i_censouf
 * @property integer $ed18_i_censomunic
 * @property integer $ed18_i_censodistrito
 * @property integer $ed18_i_censoorgreg
 * @property string $ed18_i_cnpj
 * @property integer $ed18_i_credenciamento
 * @property integer $ed18_i_locdiferenciada
 * @property integer $ed18_i_educindigena
 * @property integer $ed18_i_tipolinguain
 * @property integer $ed18_i_tipolinguapt
 * @property integer $ed18_i_linguaindigena
 * @property integer $ed18_i_categprivada
 * @property integer $ed18_i_conveniada
 * @property integer $ed18_i_cnas
 * @property integer $ed18_i_cebas
 * @property string $ed18_c_mantprivada
 * @property string $ed18_i_cnpjprivada
 * @property string $ed18_i_cnpjmantprivada
 * @property string $ed18_latitude
 * @property string $ed18_longitude
 * @property integer $ed18_codigoreferencia
 * @property integer $ed18_i_esferaadministrativa
 */
class Escola extends Model
{
    protected $table = 'escola.escola';
    protected $primaryKey = 'ed18_i_codigo';
    public $timestamps = false;
    public $incrementing = false;

    /**
     * @var array
     */
    private $storage = [];

    /**
     * @return integer
     */
    public function getCodigo()
    {
        return $this->ed18_i_codigo;
    }

    /**
     * @return string
     */
    public function getNome()
    {
        return trim($this->ed18_c_nome);
    }

    /**
     * @param $ed18_c_nome
     * @return Escola
     */
    public function setNome($ed18_c_nome)
    {
        $this->ed18_c_nome = $ed18_c_nome;
        return $this;
    }

    /**
     * @return integer
     */
    public function getCodigoReferencia()
    {
        return $this->ed18_codigoreferencia;
    }

    /**
     * @param $ed18_codigoreferencia
     * @return $this
     */
    public function setCodigoReferencia($ed18_codigoreferencia)
    {
        $this->ed18_codigoreferencia = $ed18_codigoreferencia;
        return $this;
    }

    /**
     * @return CensoMunicipio
     */
    public function getMunicipio()
    {
        if (empty($this->storage['municipio'])) {
            $this->storage['municipio'] = $this->municipio;
        }
        return $this->storage['municipio'];
    }

    /**
     * @return Departamento
     */
    public function getDepartamento()
    {
        if (empty($this->storage['departamento'])) {
            $this->storage['departamento'] = $this->departamento;
        }
        return $this->storage['departamento'];
    }

    /**
     * @param $ed18_i_codigo
     * @return $this
     */
    public function setCodigoDepartamento($ed18_i_codigo)
    {
        $this->ed18_i_codigo = $ed18_i_codigo;
        return $this;
    }

    /**
     * @return BelongsTo
     */
    public function municipio()
    {
        return $this->belongsTo(CensoMunicipio::class, 'ed18_i_censomunic', 'ed261_i_codigo');
    }

    /**
     * @return BelongsTo
     */
    public function departamento()
    {
        return $this->belongsTo(Departamento::class, 'ed18_i_codigo', 'coddepto');
    }
}
