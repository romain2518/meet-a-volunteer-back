<?php

namespace App\Controller;

use App\Repository\ExperienceRepository;
use App\Repository\ReceptionStructureRepository;
use App\Repository\ThematicRepository;
use App\Repository\UserRepository;
use App\Controller\ApiController;
use App\Entity\Experience;
use App\Repository\VolunteeringTypeRepository;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;

/**
 * @Route("api/experiences",name="api_experiences_")
 */

class ExperienceController extends ApiController
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
     * @IsGranted("ROLE_USER")
     * 
     * 
     * @param Request
     * @param ExperienceRepository
     * @return JsonResponse
     */

    public function add(
        ExperienceRepository $experienceRepository,
        Request $request,
        SerializerInterface $serializerInterface,
        ValidatorInterface $validator,
        SluggerInterface $slugger
    ): JsonResponse {

        //Si l'utilisateur qui envoie la requête n'est pas ROLE_USER, on renvoie une erreur
        if (!$this->isGranted("ROLE_USER")) {
            return $this->json(["error" => "Authorised user only"], Response::HTTP_FORBIDDEN);
        }

        //Je récupère le contenu de la requête via request->getContent
        $jsonContent = $request->getContent();

        // pour désérialiser il nous faut le composant de serialisation
        // on l'obtient avec le service SerializerInterface
        //! faire attention à ce que nous fournit l'utilisateur !!!!!

        try //essaye de deserializer la requête (= transformer l'objet JSON en Objet de l'entité donnée, ici Experience)
        {
           $newExperience = $serializerInterface->deserialize($jsonContent, Experience::class, 'json');
        } 
         catch (Exception $e) //Si le Try ne se déroule pas correctement on renvoie une exception
        {
            // dd($e);
            return $this->json("Erreur dans la syntaxe JSON", Response::HTTP_BAD_REQUEST);
        }

        //Il nous faut ensuite vérifier les infos fournis par l'utilisateur, ici nous n'avons pas de formulaire qui aurait pu contraindre les réponses de 
        // l'utilsateur $form->IsValid, on utilise donc le validator de Symfony.

        $errors = $validator->validate($newExperience);

        if (count($errors) > 0) {

            //j'utilise une méthode qui me permettra de récupérer un message en string et non un objet comme l'aurait naturellement fait un return avec $errors

            // dd($newExperience);
            return $this->json422($errors, $newExperience, 'api_experience_show');
        }

        $newExperience->setSlugTitle($slugger->slug($newExperience->getTitle())->lower());
        // on utilise la version raccourcie par le repository
        // le paramètre true, nous fait le flush() auto
        // ça correspond à persist() ET flush()



        $experienceRepository->add($newExperience, true);

        return $this->json(
            $newExperience,
            Response::HTTP_CREATED,
            [

                'Location' => $this->generateUrl('api_experiences_list_by_user', ['user_id' => $newExperience->getUser()->getId(),'limit'=> 20, 'offset'=>0])
            ],
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
