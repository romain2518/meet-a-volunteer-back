<?php

namespace App\Controller;

use App\Repository\ExperienceRepository;
use App\Repository\ReceptionStructureRepository;
use App\Repository\ThematicRepository;
use App\Repository\UserRepository;
use App\Repository\VolunteeringTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("api/experiences",name="api_experiences_")
 */

class ExperienceController extends AbstractController
{


    /**
     * @Route("/{id}", name="show_by_id",
     * 
     * methods={"GET"}, 
     * requirements={
     *      "limit"="\d+",
     *      "offset"="\d+"}
     * )
     */

    public function show(ExperienceRepository $experienceRepository, int $id): JsonResponse
    {


        return $this->json(
            $experienceRepository->find($id),
            Response::HTTP_OK,
            [],
            [
                'groups' =>
                [
                    'api_experience_show'
                ]
            ]
        );
    }

    /**
     * @Route("/", name="add", methods={"POST"})
     * 
     * 
     */

    public function create(ExperienceRepository $experienceRepository, int $id): JsonResponse
    {


        return $this->json(
            $experienceRepository->find($id),
            Response::HTTP_OK,
            [],
            [
                'groups' =>
                [
                    'api_experience_show'
                ]
            ]
        );
    }






    /**
     * @Route("/{limit}/{offset}", name="list",
     * 
     * methods={"GET"}, 
     * requirements={
     *      "limit"="\d+",
     *      "offset"="\d+"}
     * )
     */

    public function list(ExperienceRepository $experienceRepository, int $limit, int $offset): JsonResponse
    {


        return $this->json(
            $experienceRepository->findBy([], null, $limit, $offset),
            Response::HTTP_OK,
            [],
            [
                'groups' =>
                [
                    'api_experience_list'
                ]
            ]
        );
    }


    /**
     * @Route("/{user_id}/{limit}/{offset}", name="list_by_user",
     * 
     * methods={"GET"}, 
     *  requirements={
     *      "user_id"="\d+",
     *      "limit"="\d+",
     *      "offset"="\d+"}
     * )
     */

    public function listByUser(ExperienceRepository $experienceRepository, UserRepository $userRepository, int $user_id, int $limit, int $offset): JsonResponse
    {

        return $this->json(
            $experienceRepository->findBy(["user" => $userRepository->find($user_id)], null, $limit, $offset),
            Response::HTTP_OK,
            [],
            [
                'groups' =>
                [
                    'api_experience_list'
                ]
            ]
        );
    }


    /**
     * @Route("/latest/{limit}/{offset}", name="list_by_latest",
     * 
     * methods={"GET"}, 
     *  requirements={
     *      "limit"="\d+",
     *      "offset"="\d+"}
     * )
     */


    public function listByLatest(ExperienceRepository $experienceRepository, int $limit, int $offset): JsonResponse
    {

        return $this->json(
            $experienceRepository->findBy([], ['createdAt' => 'DESC'], $limit, $offset),
            Response::HTTP_OK,
            [],
            [
                'groups' =>
                [
                    'api_experience_list'
                ]
            ]
        );
    }

    /**
     * @Route("/random/{limit}/{offset}", name="list_by_random",
     * 
     * methods={"GET"}, 
     *  requirements={
     *      "limit"="\d+",
     *      "offset"="\d+"}
     * )
     */


    //TODO le tri par random, faire ma propre requete dql ?

    public function listByRandom(ExperienceRepository $experienceRepository, int $limit, int $offset): JsonResponse
    {

        return $this->json(
            $experienceRepository->findBy([], ['createdAt' => 'RANDOM'], $limit, $offset),
            Response::HTTP_OK,
            [],
            [
                'groups' =>
                [
                    'api_experience_list'
                ]
            ]
        );
    }

    /**
     * @Route("/views/{limit}/{offset}", name="list_by_views",
     * 
     * methods={"GET"}, 
     *  requirements={
     *      "limit"="\d+",
     *      "offset"="\d+"}
     * )
     */

    public function listByViews(ExperienceRepository $experienceRepository, int $limit, int $offset): JsonResponse
    {

        return $this->json(
            $experienceRepository->findBy([], ['views' => 'DESC'], $limit, $offset),
            Response::HTTP_OK,
            [],
            [
                'groups' =>
                [
                    'api_experience_list'
                ]
            ]
        );
    }

    /**
     * @Route("/volunteeringType/{id}/{limit}/{offset}", name="list_by_volunteering_type",
     * 
     * methods={"GET"}, 
     *  requirements={
     *      "id"="\d+",
     *      "limit"="\d+",
     *      "offset"="\d+"}
     * )
     */


    public function listByVolunteeringType(ExperienceRepository $experienceRepository, VolunteeringTypeRepository $volunteeringTypeRepository, int $id, int $limit, int $offset)
    {

        return $this->json(

            $experienceRepository->findBy(["volunteeringType" => $volunteeringTypeRepository->find($id)], null, $limit, $offset),
            Response::HTTP_OK,
            [],
            [
                'groups' =>
                [
                    'api_experience_list'
                ]
            ]
        );
    }


    /**
     * @Route("/receptionStructure/{id}/{limit}/{offset}", name="list_by_reception_structure",
     * 
     * methods={"GET"}, 
     *  requirements={
     *      "id"="\d+",
     *      "limit"="\d+",
     *      "offset"="\d+"}
     * )
     */


    public function listByReceptionStructure(ExperienceRepository $experienceRepository, ReceptionStructureRepository $receptionStructureRepository, int $id, int $limit, int $offset)
    {

        return $this->json(

            $experienceRepository->findBy(["receptionStructure" => $receptionStructureRepository->find($id)], null, $limit, $offset),
            Response::HTTP_OK,
            [],
            [
                'groups' =>
                [
                    'api_experience_list'
                ]
            ]
        );
    }

    /**
     * @Route("/thematic/{id}/{limit}/{offset}", name="list_by_thematic",
     * 
     * methods={"GET"}, 
     *  requirements={
     *      "id"="\d+",
     *      "limit"="\d+",
     *      "offset"="\d+"}
     * )
     */


    //TODO Je fais appel à des donnés stockées dans une table intémédiaire. Faire une jointure ? 


    public function listByThematic(ExperienceRepository $experienceRepository, ThematicRepository $thematicRepository, int $id, int $limit, int $offset)
    {

        return $this->json(

            $experienceRepository->findBy(["thematicId" => $thematicRepository->find($id)], null, $limit, $offset),
            Response::HTTP_OK,
            [],
            [
                'groups' =>
                [
                    'api_experience_list'
                ]
            ]
        );
    }





}
