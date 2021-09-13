<?php

namespace App\Controller;

use App\Entity\Especialidade;
use App\Repository\EspecialidadeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EspecialidadeController extends AbstractController {

    private $entityManager;
    private EspecialidadeRepository $especialidadeRepository;

    public function __construct(EntityManagerInterface $entityManager, EspecialidadeRepository $especialidadeRepository) {
        $this->entityManager = $entityManager;
        $this->especialidadeRepository = $especialidadeRepository;
    }

    #[Route('/especialidades', methods: 'POST')]
    public function novo(Request $request): Response {
        $corpoRequisicao = $request->getContent();
        $objJson = json_decode($corpoRequisicao);

        $especialidade = new Especialidade();
        $especialidade->setDescricao($objJson->descricao);

        $this->entityManager->persist($especialidade);
        $this->entityManager->flush();

        return new JsonResponse($especialidade);
    }

    #[Route('/especialidades', methods: 'GET')]
    public function getAll(): Response {
        return new JsonResponse($this->especialidadeRepository->findAll());
    }

    #[Route('/especialidades/{id}', methods: 'GET')]
    public function getById(int $id): Response {
        $especialidade = $this->especialidadeRepository->find($id);
        $response = is_null($especialidade) ? Response::HTTP_NO_CONTENT : 200;

        return new JsonResponse($especialidade, $response);
    }

    #[Route('/especialidades/{id}', methods: 'PUT')]
    public function updateById(int $id, Request $request): Response {
        $corpoRequisicao = $request->getContent();
        $objJson = json_decode($corpoRequisicao);

        $especialidade = $this->especialidadeRepository->find($id);
        if (is_null($especialidade)){
            return new Response('', Response::HTTP_NOT_FOUND);
        }

        $especialidade->setDescricao($objJson->descricao);

        $this->entityManager->flush();

        return new JsonResponse($especialidade);
    }

    #[Route('/especialidades/{id}', methods: 'DELETE')]
    public function deleteById(int $id): Response {
        $especialidade = $this->especialidadeRepository->find($id);
        $this->entityManager->remove($especialidade);
        $this->entityManager->flush();

        return new Response('', Response::HTTP_NO_CONTENT);
    }
}
