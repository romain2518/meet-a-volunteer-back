<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Exception;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;


class UserController extends AbstractController
{
    //? User list
    /**
     * @Route(
     * "/api/user/{limit}/{offset}",
     * name="api_user_list",
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

    //? User list Most experienced
    /**
     * @Route(
     * "/api/user/mostExperienced/{limit}/{offset}",
     * name="api_user_listMostExperienced",
     * methods={"GET"},
     * requirements={
     * "limit"="\d+",
     * "offset"="\d+"
     * }
     * )
     */
    public function listMostExperienced(UserRepository $userRepository, int $limit, int $offset): JsonResponse
     {
         return $this->json(
             $userRepository->findBy([], null, $limit, $offset),
             Response::HTTP_OK,
             [],
             [
                 'groups' => [
                     'api_user_list'
                 ]
            ]
         );
     }

     //? show one user
    /**
     * @Route(
     * "/api/user/{id}",
     * name="api_user_show",
     * methods={"GET"},
     * requirements={
     * "id"="\d+"
     * }
     * )
     */
     public function show(User $user = null): JsonResponse
     {
         if ($user === null)
         {
             // on renvoie donc une 404
             return $this->json(
                 [
                     "erreur" => "l'utilisateur n'a pas été trouvé",
                     "code_error" => 404
                 ],
                 Response::HTTP_NOT_FOUND,// 404
             );
            }

         return $this->json(
             $user,
             Response::HTTP_OK,
             [],
             [
                 'groups' => [
                     'api_user_show'
                 ]
             ]
         );
     }

     //? delete User
      /**
     * @Route(
     * "/api/user/{id}",
     * name="api_user_delete",
     * methods={"DELETE"},
     * requirements={"id"="\d+"}
     * )
     *
     * @param User $user
     */
    public function delete(User $user = null, UserRepository $repo)
    {
        if ($user === null)
        {
            // on renvoie donc une 404
            return $this->json(
                [
                    "erreur" => "l'utilisateur n'a pas été trouvé",
                    "code_error" => 404
                ],
                Response::HTTP_NOT_FOUND,// 404
            );
           }

        $repo->remove($user, true);
        return $this->json(
            null,
            Response::HTTP_NO_CONTENT,
            [
                'Location' => $this->generateUrl('api_user_list')
            ]
            );
    }

     //? create user
     /**
      * @Route(
      * "/api/user",
      * name="api_user_create",
      * methods={"POST"},
      * )
      * 
      * @param Request $request
      * @param UserRepository $userRepository
      * @param SerializerInterface $serializerInterface
      * @param ValidatorInterface $validatorInterface
      * @return JsonResponse
      */
     public function create(
         Request $request,
         SerializerInterface $serializerInterface,
         UserRepository $userRepository,
         ValidatorInterface $validatorInterface
     ): JsonResponse
     {
         //récupérer le contenu JSON
         $jsonContent = $request->getContent();

         //vérifier ce que fournit l'user
         try{
             $newUser = $serializerInterface->deserialize($jsonContent, User::class, 'json');
         }

         catch(Exception $e)
         {
            return $this->json("Le JSON est mal formé", Response::HTTP_BAD_REQUEST);
         }
    
         //valider les infos
         $errors = $validatorInterface->validate($newUser);

         if (count($errors)> 0)
        {
            return $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        //faire l'insertion
        $userRepository->add($newUser, true);

        //retour insertion ok
        //fournir objet créé pour que l'user puisse avoir l'id
        return $this->json(
            $newUser,
            Response::HTTP_CREATED,
            [
                'Location' => $this->generateUrl('api_user_show', ['id' => $newUser->getId()])
            ]
            );

     }

}
