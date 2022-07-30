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
use App\Service\FileUploader;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\AbstractObjectNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

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
     *      "id"="\d+",
     *      }
     * )
     */

    public function show(Experience $experience = null, ManagerRegistry $doctrine): JsonResponse
    {

        // ParamConverter convert the $id in an object (here $experience) and is used instead of experienceRepository->find($id)

        if ($experience === null) {
            return $this->json(
                'Error: Experience not available',
                Response::HTTP_NOT_FOUND
            );
        }

        // Add 1 view
        $experience->setViews($experience->getViews() + 1);
        $doctrine->getManager()->flush();

        return $this->json(
            $experience,
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
     * @Route("", name="add", methods={"POST"})
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


        //Je récupère le contenu de la requête via request->getContent
        $jsonContent = $request->getContent();

        // pour désérialiser il nous faut le composant de serialisation
        // on l'obtient avec le service SerializerInterface
        //! faire attention à ce que nous fournit l'utilisateur !!!!!

        try //essaye de deserializer la requête (= transformer l'objet JSON en Objet de l'entité donnée, ici Experience)
        {
            /** @var Experience */
            $newExperience = $serializerInterface->deserialize($jsonContent, Experience::class, 'json');
        } catch (Exception $e) //Si le Try ne se déroule pas correctement on renvoie une exception
        {
            // dd($e);
            return $this->json("Error bad request", Response::HTTP_BAD_REQUEST);
        }

        //Il nous faut ensuite vérifier les infos fournis par l'utilisateur, ici nous n'avons pas de formulaire qui aurait pu contraindre les réponses de 
        // l'utilsateur $form->IsValid, on utilise donc le validator de Symfony.

        $errors = $validator->validate($newExperience);

        if (count($errors) > 0) {

            //j'utilise une méthode qui me permettra de récupérer un message en string et non un objet comme l'aurait naturellement fait un return avec $errors

            // dd($newExperience);
            return $this->json422($errors, $newExperience, 'api_experience_show');
        }

        $newExperience->setUser($this->getUser());
        $newExperience->setSlugTitle($slugger->slug($newExperience->getTitle())->lower());
        $newExperience->setViews(0);
        $newExperience->setCreatedAt(null);
        $newExperience->setUpdatedAt(null);

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
     * @Route("/{id}", name="edit", methods={"PUT", "PATCH", "POST"},
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
        Experience $experience = null,
        Request $request,
        SluggerInterface $slugger,
        ValidatorInterface $validator,
        ManagerRegistry $doctrine,
        FileUploader $fileUploader,
        Filesystem $fileSystem
    ): JsonResponse {
        //? Case Experience not found
        if ($experience === null) {return $this->json('Error: Experience not found',Response::HTTP_NOT_FOUND);}

        //? Saving non-modifiable datas
        $actualViewsCounter = $experience->getViews();
        $actualCreatedAt = $experience->getCreatedAt();
        $actualPicture = $experience->getPicture();

        //? If connected user is the writer
        $this->denyAccessUnlessGranted('EXPERIENCE_EDIT', $experience);

        //? Accessing and denormalizing new datas
        $requestContent = $request->request->all();

        $normalizer = new ObjectNormalizer(null, new CamelCaseToSnakeCaseNameConverter());
        // @see https://symfony.com/doc/current/components/serializer.html#deserializing-in-an-existing-object

        try {
            $normalizer->denormalize($requestContent, Experience::class, null, [AbstractNormalizer::OBJECT_TO_POPULATE => $experience]);
        } catch (Exception $e) {
            return $this->json("Error bad request", Response::HTTP_BAD_REQUEST);
        }

        //? Validating datas
        $errors = $validator->validate($experience);
        if (count($errors) > 0) {
            return $this->json422($errors, $experience, 'api_experience_show');
        }

        //? Setting non-modifiable datas back
        $experience->setSlugTitle($slugger->slug($experience->getTitle())->lower());
        $experience->setViews($actualViewsCounter);
        $experience->setCreatedAt($actualCreatedAt);
        $experience->setPicture($actualPicture);

        //? File management
        if (!empty($request->files->get('pictureFile'))) {
            $file = $request->files->get('pictureFile');

            $uploadResponse = $fileUploader->upload($file, 'experience');
            if ($uploadResponse['isFailed']) {
               return $this->json($uploadResponse['error'], $uploadResponse['responseCode']);
            }

            if ($experience->getPicture() !== '0.jpg') {
                $fileSystem->remove('images/experiencePicture/' . $experience->getPicture());
            }
    
            $experience->setPicture($uploadResponse['filename']);
        }

        //? Saving
        $doctrine->getManager()->flush();

        //? Returning JSON response
        return $this->json(
            $experience,
            Response::HTTP_PARTIAL_CONTENT,
            [
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
    public function delete(Experience $experience = null, ExperienceRepository $experienceRepository)
    {
        //If experience exists
        if ($experience === null) {

            return $this->json(
                'Error: Experience not found',
                Response::HTTP_NOT_FOUND
            );
        }

        //If connected user is the writer
        $this->denyAccessUnlessGranted('EXPERIENCE_EDIT', $experience);

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

        
       $user = $userRepository->find($user_id);
       
       if ($user === null) 
       {
        return $this->json(
            'Error: User not found',
            Response::HTTP_NOT_FOUND
        );

       }

        return $this->json(
            $experienceRepository->findBy(["user" => $user], null, $limit, $offset),
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

        $volunteeringTypeId = $volunteeringTypeRepository->find($id);

        if ($volunteeringTypeId === null) {
            
            return $this->json(
                'Error: VolunteeringType not found',
                Response::HTTP_NOT_FOUND
            );
        }

        return $this->json(

            $experienceRepository->findBy(["volunteeringType" => $volunteeringTypeId ], null, $limit, $offset),
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
        $receptionStructure = $receptionStructureRepository->find($id);

        if ($receptionStructure === null) {
            return $this->json(
                'Error: Reception structure not found',
                Response::HTTP_NOT_FOUND
            );
        }
        
        return $this->json(
            $experienceRepository->findBy(["receptionStructure" => $receptionStructure], null, $limit, $offset),
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


    public function listByThematic(int $limit, Thematic $thematic = null, int $offset)
    {

        if ($thematic === null) {
            return $this->json(
                'Error: Thematic not found',
                Response::HTTP_NOT_FOUND
            );
        }

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
