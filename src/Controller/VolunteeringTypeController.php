<?php

namespace App\Controller;

use App\Repository\VolunteeringTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VolunteeringTypeController extends AbstractController
{
    /**
     * @Route(
     *  "/api/volunteeringType/{limit}/{offset}", 
     *  name="app_thematic_list", 
     *  methods={"GET"}, 
     *  requirements={
     *      "limit"="\d+",
     *      "offset"="\d+"
     *  }
     * )
     */
    public function list(VolunteeringTypeRepository $volunteeringTypeRepo, int $limit, int $offset): JsonResponse
    {
        return $this->json(
            $volunteeringTypeRepo->findBy([], null, $limit, $offset),
            Response::HTTP_OK,
            [],
            [
                'groups' => [
                    'api_volunteering_type_list'
                ]
            ]

        );
    }
}
