<?php

namespace App\Controller;

use App\Repository\ThematicRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ThematicController extends AbstractController
{
    /**
     * @Route(
     *  "/api/thematic/{limit}/{offset}", 
     *  name="app_thematic_list", 
     *  methods={"GET"}, 
     *  requirements={
     *      "limit"="\d+",
     *      "offset"="\d+"
     *  }
     * )
     */
    public function list(ThematicRepository $thematicRepo, int $limit, int $offset): JsonResponse
    {
        return $this->json(
            $thematicRepo->findBy([], null, $limit, $offset),
            Response::HTTP_OK,
            [],
            [
                'groups' => [
                    'api_thematic_list'
                ]
            ]

        );
    }
}
