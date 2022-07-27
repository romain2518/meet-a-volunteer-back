<?php

namespace App\Controller;

use App\Repository\ReceptionStructureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ReceptionStructureController extends AbstractController
{
    /**
     * @Route(
     *  "/api/receptionStructure/{limit}/{offset}", 
     *  name="app_reception_structure_list", 
     *  methods={"GET"}, 
     *  requirements={
     *      "limit"="\d+",
     *      "offset"="\d+"
     *  }
     * )
     */
    public function list(ReceptionStructureRepository $receptionStructureRepo, int $limit, int $offset): JsonResponse
    {
        return $this->json(
            $receptionStructureRepo->findBy([], null, $limit, $offset),
            Response::HTTP_OK,
            [],
            [
                'groups' => [
                    'api_reception_structure_list'
                ]
            ]

        );
    }
}
