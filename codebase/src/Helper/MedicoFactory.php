<?php

namespace App\Helper;

use App\Entity\Medico;
use App\Repository\EspecialidadeRepository;

class MedicoFactory {

    private EspecialidadeRepository $especialidadeRepository;

    public function __construct(EspecialidadeRepository $especialidadeRepository) {

        $this->especialidadeRepository = $especialidadeRepository;
    }

    public function criarMedico(string $json): Medico {
        $objJson = json_decode($json);
        $especialidadeId = $objJson->especialidadeId;
        $especialidade = $this->especialidadeRepository->find($especialidadeId);

        $medico = new Medico();
        $medico->setCrm($objJson->crm)
            ->setNome($objJson->nome)
            ->setEspecialidade($especialidade);

        return $medico;
    }

}