<?php
namespace App\Domain\Patrimonial\Protocolo\Model;

use App\Domain\Financeiro\Planejamento\Models\Comissao;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Cgm
 * @package App\Domain\Patrimonial\Patrimonio
 */
class Cgm extends Model
{
    protected $table = 'protocolo.cgm';

    protected $primaryKey = 'z01_numcgm';
    protected $appends = ["nascimentoMask","cpfCgcMask"];
    protected $fillable = [
        "z01_numcgm",
        "z01_nome",
        "z01_ender",
        "z01_numero",
        "z01_compl",
        "z01_bairro",
        "z01_munic",
        "z01_uf",
        "z01_cep",
        "z01_cxpostal",
        "z01_cadast",
        "z01_telef",
        "z01_ident",
        "z01_login",
        "z01_incest",
        "z01_telcel",
        "z01_email",
        "z01_endcon",
        "z01_numcon",
        "z01_comcon",
        "z01_baicon",
        "z01_muncon",
        "z01_ufcon",
        "z01_cepcon",
        "z01_cxposcon",
        "z01_telcon",
        "z01_celcon",
        "z01_emailc",
        "z01_nacion",
        "z01_estciv",
        "z01_profis",
        "z01_tipcre",
        "z01_cgccpf",
        "z01_fax",
        "z01_nasc",
        "z01_pai",
        "z01_mae",
        "z01_sexo",
        "z01_ultalt",
        "z01_contato",
        "z01_hora",
        "z01_nomefanta",
        "z01_cnh",
        "z01_categoria",
        "z01_dtemissao",
        "z01_dthabilitacao",
        "z01_nomecomple",
        "z01_dtvencimento",
        "z01_dtfalecimento",
        "z01_escolaridade",
        "z01_naturalidade",
        "z01_identdtexp",
        "z01_identorgao",
        "z01_trabalha",
        "z01_renda",
        "z01_localtrabalho",
        "z01_pis",
        "z01_obs",
    ];

    public function comissoes()
    {
        $this->hasMany(Comissao::class, 'pl3_cgm', 'z01_numcgm');
    }

    public $timestamps = false;

    public function getNascimentoMaskAttribute()
    {
        if (empty($this->attributes["z01_nasc"])) {
            return $this->attributes["z01_nasc"];
        }
        $nascimento = new \DateTime($this->attributes["z01_nasc"]);
        return $nascimento->format('d/m/Y');
    }

    public function getCpfCgcMaskAttribute()
    {
        return db_cgccpf($this->attributes["z01_cgccpf"]);
    }

    public function enderecos()
    {
        return $this->hasMany(CgmEndereco::class, "z07_numcgm");
    }

    public function enderecoPrimario()
    {
        return $this->hasOne(CgmEndereco::class, "z07_numcgm")->where("z07_tipo", "=", "P");
    }
}
