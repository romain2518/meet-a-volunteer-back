<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;


class UserController extends AbstractController
{
    /**
     * @Route(
     * "/api/user/{limit}/{offset}",
     * name="app_user_list",
     * methods={"GET"},
     * requirements={
     * "limit"="\d+",
     * "offset"="\d+"
     * }
     * )
     */
    public function list(UserRepository $userRepository, int $limit, int $offset): JsonResponse
    {
        return $this->json(
            $userRepository->findby([], null, $limit, $offset),
            Response::HTTP_OK,
            [],
            [
                'groups' => [
                    'api_user_list'
                ]
            ]
        );
    }
}
