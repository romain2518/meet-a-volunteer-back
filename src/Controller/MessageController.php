<?php

namespace App\Controller;

use App\Entity\Message;
use App\Entity\User;
use App\Repository\MessageRepository;
use Doctrine\Persistence\ManagerRegistry;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class MessageController extends AbstractController
{
    /**
     * @Route(
     * "/api/receivedMessages/{id}/{limit}/{offset}",
     * name="api_messages_list_received",
     * methods={"GET"},
     * requirements={
     *    "id"="\d+",
     *    "limit"="\d+",
     *    "offset"="\d+"
     *  }
     * )
     */
    public function listReceived(MessageRepository $messageRepository, User $user=null, int $limit, int $offset): JsonResponse
    {
        if ($user === null) {return $this->json('Error: User not found...', Response::HTTP_NOT_FOUND,);}

        $this->denyAccessUnlessGranted('MESSAGE_VIEW', $user);

        return $this->json(
            $messageRepository->findBy(['userReceiver'=>$user], null, $limit, $offset),
            Response::HTTP_OK,
            [],
            [
                'groups'=>[
                    'api_message_list'
                ]
            ]
        );
    }

    /**
     * @Route(
     * "/api/sentMessages/{id}/{limit}/{offset}",
     * name="api_messages_list_sent",
     * methods={"GET"},
     * requirements={
     *    "id"="\d+",
     *    "limit"="\d+",
     *    "offset"="\d+"
     *  }
     * )
     */
    public function listSent(MessageRepository $messageRepository, User $user=null, int $limit, int $offset): JsonResponse
    {
        if ($user === null) {return $this->json('Error: User not found...', Response::HTTP_NOT_FOUND,);}

        $this->denyAccessUnlessGranted('MESSAGE_VIEW', $user);

        return $this->json(
            $messageRepository->findBy(['userSender'=>$user], null, $limit, $offset),
            Response::HTTP_OK,
            [],
            [
                'groups'=>[
                    'api_message_list'
                ]
            ]
        );
    }

    /**
     * @Route(
     * "/api/message",
     * name="api_messages_create",
     * methods={"POST"},
     * )
     */
    public function create(
        Request $request,
        SerializerInterface $serializerInterface,
        MessageRepository $messageRepository,
        ValidatorInterface $validatorInterface
    )
    {
        //récupérer le contenu JSON
        $jsonContent = $request->getContent();

        //vérifier ce que fournit l'user
        try{
            $newMessage = $serializerInterface->deserialize($jsonContent, Message::class, 'json');
        }

        catch(Exception $e)
        {
           return $this->json("Error: bad request", Response::HTTP_BAD_REQUEST);
        }
   
        //valider les infos
        $errors = $validatorInterface->validate($newMessage);

        if (count($errors)> 0)
       {
           return $this->json($errors, Response::HTTP_UNPROCESSABLE_ENTITY);
       }

       $newMessage->setUserSender($this->getUser());

       //faire l'insertion
       $messageRepository->add($newMessage, true);

       //retour insertion ok
       //fournir objet créé pour que l'user puisse avoir l'id
       return $this->json(
           $newMessage,
           Response::HTTP_CREATED,
           [
               'Location' => $this->generateUrl('api_messages_list_sent', ['id' => $newMessage->getUserSender()->getId(), 'limit' => 20, 'offset' => 0])
           ],
           [
               'groups' =>
               [
                   'api_message_show'
               ]
           ]
       );
    }

    /**
     * @Route(
     * "/api/message/{id}/setRead",
     * name="api_messages_set_read",
     * methods={"GET"},
     * requirements={"id"="\d+"}
     * )
     */
    public function setRead(MessageRepository $messageRepository, Message $message=null, ManagerRegistry $doctrine): JsonResponse
    {
        if ($message === null) {return $this->json('Error: Message not found...', Response::HTTP_NOT_FOUND,);}

        $this->denyAccessUnlessGranted('MESSAGE_EDIT', $message);

        $message->setIsRead(true);
        
        $doctrine->getManager()->flush();

        return $this->json(
            $message,
            Response::HTTP_PARTIAL_CONTENT,
            [],
            [
                'groups'=>[
                    'api_message_show'
                ]
            ]
        );
    }

    /**
     * @Route(
     * "/api/message/{id}/setNotRead",
     * name="api_messages_set_not_read",
     * methods={"GET"},
     * requirements={"id"="\d+"}
     * )
     */
    public function setNotRead(MessageRepository $messageRepository, Message $message=null, ManagerRegistry $doctrine): JsonResponse
    {
        if ($message === null) {return $this->json('Error: Message not found...', Response::HTTP_NOT_FOUND,);}
        
        $this->denyAccessUnlessGranted('MESSAGE_EDIT', $message);

        $message->setIsRead(false);
        
        $doctrine->getManager()->flush();

        return $this->json(
            $message,
            Response::HTTP_PARTIAL_CONTENT,
            [],
            [
                'groups'=>[
                    'api_message_show'
                ]
            ]
        );
    }

    /**
     * @Route(
     * "/api/message/{id}",
     * name="api_message_delete",
     * methods={"DELETE"},
     * requirements={"id"="\d+"}
     * )
     *
     * @param Message $message
     */
    public function delete(Message $message = null, MessageRepository $repo)
    {
        if ($message === null) {return $this->json('Error: Message not found...', Response::HTTP_NOT_FOUND);}
        
        $this->denyAccessUnlessGranted('MESSAGE_EDIT', $message);

        $repo->remove($message, true);

        return $this->json(
            null,
            Response::HTTP_NO_CONTENT,
            [
                'Location' => $this->generateUrl('api_messages_list_sent', ['id' => $message->getUserSender()->getId(), 'limit' => 20, 'offset' => 0])
            ],
            [
                'groups' =>
                [
                    'api_message_show'
                ]
            ]
        );
    }
}
