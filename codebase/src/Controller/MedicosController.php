<?php

namespace App\Controller;

use App\Entity\Medico;
use App\Helper\MedicoFactory;
use App\Repository\MedicoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MedicosController extends AbstractController {

    private $entityManager;
    private MedicoFactory $medicoFactory;
    private MedicoRepository $medicoRepository;

    public function __construct(EntityManagerInterface $entityManager,
                                MedicoFactory $medicoFactory,
                                MedicoRepository $medicoRepository) {
        $this->entityManager = $entityManager;
        $this->medicoFactory = $medicoFactory;
        $this->medicoRepository = $medicoRepository;
    }

    #[Route('/medicos', methods: 'POST')]
    public function novo(Request $request): Response {
        $corpoRequisicao = $request->getContent();
        $medico = $this->medicoFactory->criarMedico($corpoRequisicao);

        $this->entityManager->persist($medico);
        $this->entityManager->flush();

        return new JsonResponse($medico);
    }

    #[Route('/medicos', methods: 'GET')]
    public function getAll(): Response {
        return new JsonResponse($this->medicoRepository->findAll());
    }

    #[Route('/medicos/{id}', methods: 'GET')]
    public function getById(int $id): Response {
        $medico = $this->getMedico($id);
        $response = is_null($medico) ? Response::HTTP_NO_CONTENT : 200;

        return new JsonResponse($medico, $response);
    }

    #[Route('/medicos/{id}', methods: 'PUT')]
    public function updateById(int $id, Request $request): Response {
        $corpoRequisicao = $request->getContent();
        $medicoEnviado = $this->medicoFactory->criarMedico($corpoRequisicao);

        $medico = $this->getMedico($id);
        if (is_null($medico)){
            return new Response('', Response::HTTP_NOT_FOUND);
        }

        $medico->setCrm($medicoEnviado->getCrm())
            ->setNome($medicoEnviado->getNome())
            ->setEspecialidade($medicoEnviado->getEspecialidade());

        $this->entityManager->flush();

        return new JsonResponse($medico);
    }

    #[Route('/medicos/{id}', methods: 'DELETE')]
    public function deleteById(int $id): Response {
        $medico = $this->getMedico($id);
        $this->entityManager->remove($medico);
        $this->entityManager->flush();

        return new Response('', Response::HTTP_NO_CONTENT);
    }

    #[Route('/especialidades/{especialidadeId}/medicos', methods: 'GET')]
    public function getMedicosByEspecialidade(int $especialidadeId):Response {
        $medicos = $this->medicoRepository->findBy([
            'especialidade' => $especialidadeId
        ]);

        return new JsonResponse($medicos);
    }

    private function getMedico(int $id): Medico {
        $medico = $this->medicoRepository->find($id);
        return $medico;
    }
}