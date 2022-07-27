<?php

namespace App\Controller;

use App\Repository\ExperienceRepository;
use App\Repository\ReceptionStructureRepository;
use App\Repository\ThematicRepository;
use App\Repository\UserRepository;
use App\Controller\ApiController;
use App\Entity\Experience;
use App\Entity\Thematic;
use App\Repository\VolunteeringTypeRepository;
use Doctrine\Persistence\ManagerRegistry;
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
        } catch (Exception $e) //Si le Try ne se déroule pas correctement on renvoie une exception
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

                'Location' => $this->generateUrl('api_experiences_list_by_user', ['user_id' => $newExperience->getUser()->getId(), 'limit' => 20, 'offset' => 0])
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
     * @Route("/{id}", name="edit", methods={"PUT", "PATCH"},
     * 
     * requirements={"id"="\d+"})
     * 
     * @IsGranted("ROLE_USER")
     * 
     * 
     * @param Request
     * @param ExperienceRepository
     * @param SerializerInterface $serializerInterface
     * @return JsonResponse
     */

    public function edit(
        Experience $experience,
        Request $request,
        ExperienceRepository $experienceRepository,
        SerializerInterface $serializerInterface,
        ManagerRegistry $doctrine
    ): JsonResponse {

        //Si l'objet Json est vide on renvoie une 404, car il n'ya rien à modifier

        if ($experience === null) {
            return $this->json(
                $experience,
                Response::HTTP_NOT_FOUND,
            );
        }

        $jsonContent = $request->getContent();


        // Pour mettre à jour une entité avec le deserializer dans le contexte d’une requête api, il faut utiliser le AbstractNormalizer.
        $serializerInterface->deserialize($jsonContent, Experience::class, 'json', [AbstractNormalizer::OBJECT_TO_POPULATE => $experience]);


        $doctrine->getManager()->flush();

        return $this->json(

            $experience,
            Response::HTTP_PARTIAL_CONTENT,
            [
                // Nom de l'en-tête + URL
                'Location' => $this->generateUrl('api_experiences_list_by_user', ['user_id' => $experience->getUser()->getId(), 'limit' => 20, 'offset' => 0])
            ],
            [
                "groups" => "api_experience_show"
            ]
        );
    }

    /**
     * @Route("/{id}",name="delete", methods={"DELETE"}, requirements={"id"="\d+"})
     *
     * @param Experience $experience
     */
    public function delete(?Experience $experience, ExperienceRepository $experienceRepository)
    {

        if ($experience === null) {

            return $this->json(
                $experience,
                Response::HTTP_NOT_FOUND,
            );
        }


        $experienceRepository->remove($experience, true);

        return $this->json(
            null,
            Response::HTTP_NO_CONTENT,
            [

                'Location' => $this->generateUrl('api_experiences_list_by_user', ['user_id' => $experience->getUser()->getId(), 'limit' => 20, 'offset' => 0])
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




    public function listByRandom(ExperienceRepository $experienceRepository, int $limit, int $offset): JsonResponse
    {

        return $this->json(
            $experienceRepository->findByRandom($limit, $offset),
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

     
    public function listByThematic(int $limit, Thematic $thematic, int $offset)
    {
        $experiences = $thematic->getExperiences();
        $slicedList = $experiences->slice($offset, $limit);


        return $this->json(
            // $experienceRepository->findByThematic($id, $limit, $offset),
            $slicedList,
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