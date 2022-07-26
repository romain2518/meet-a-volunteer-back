<?php

namespace App\Controller;

use App\Model\JsonError;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;


class ApiController extends AbstractController
{
    public function json422($errors, $data, $groups)
    {
        $messages = [];

        for ($i = 0; $i < count($errors); $i++) {
            $messages['error' . $i] = $errors[$i]->getMessage();
        }

        return $this->json(
            [$data, $messages],
            Response::HTTP_UNPROCESSABLE_ENTITY,
            [],
            [
                'groups' =>
                [
                    $groups
                ]
            ]
        );
    }
}