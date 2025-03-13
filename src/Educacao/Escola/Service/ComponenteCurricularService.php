<?php


namespace ECidade\Educacao\Escola\Service;

use ECidade\Educacao\Escola\Model\ComponenteCurricular;
use ECidade\Educacao\Escola\Registry\AreaConhecimentoRegistry;
use ECidade\Educacao\Escola\Registry\CensoDisciplinaRegistry;
use ECidade\Educacao\Escola\Registry\ComponenteCurricularRegistry;
use ECidade\Educacao\Escola\Repository\ComponenteCurricularRepository;
use Exception;

/**
 * Class ComponenteCurricularService
 * @package ECidade\Educacao\Escola\Service
 */
class ComponenteCurricularService
{
    public function get()
    {
        $repository = new ComponenteCurricularRepository();
        $repository->withDisciplinaCenso()->get();
        return $repository->get();
    }

    public function salvar($parametros)
    {
        if (empty($parametros->nome)) {
            throw new Exception('Informe o "Nome " da disciplina.');
        }
        if (empty($parametros->nomeCompleto)) {
            throw new Exception('Informe o "Nome Completo" da disciplina.');
        }
        if (empty($parametros->sigla)) {
            throw new Exception('Informe a "Sigla" da disciplina.');
        }
        if (empty($parametros->censoDisciplina)) {
            throw new Exception('Informe ao menos uma "Disciplina do Censo".');
        }

        $disciplina =  new ComponenteCurricular();
        $disciplina->setCodigo($parametros->codigo);
        $disciplina->setNome($parametros->nome);
        $disciplina->setNomeCompleto($parametros->nomeCompleto);
        $disciplina->setSigla($parametros->sigla);
        $disciplina->setCorHtml($parametros->corhtml);

        if (!empty($parametros->codigoArea)) {
            $disciplina->setAreaConhecimento(AreaConhecimentoRegistry::get($parametros->codigoArea));
        }

        foreach ($parametros->censoDisciplina as $codigo) {
            $disciplina->addCensoDisciplina(CensoDisciplinaRegistry::get($codigo));
        }

        $repository = new ComponenteCurricularRepository();
        $disciplina = $repository->salvar($disciplina);

        return $disciplina;
    }

    /**
     * @param $parametros
     * @return bool
     * @throws Exception
     */
    public function excluir($parametros)
    {
        if (empty($parametros->codigo)) {
            throw new Exception("Informe a disciplina.");
        }
        $disciplina = ComponenteCurricularRegistry::get($parametros->codigo);

        $repository = new ComponenteCurricularRepository();
        if ($repository->possueVinculoEnsino($disciplina)) {
            throw new Exception("Disciplina possui vínculo com Ensino e não pode ser excluída.");
        }

        $repository->excluir($disciplina);
        return true;
    }
}
