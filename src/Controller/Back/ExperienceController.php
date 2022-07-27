<?php

namespace App\Controller\Back;

use App\Entity\Experience;
use App\Form\ExperienceType;
use App\Repository\ExperienceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/back/experience")
 */
class ExperienceController extends AbstractController
{
    /**
     * @Route("/", name="app_back_experience_index", methods={"GET"})
     */
    public function index(ExperienceRepository $experienceRepository): Response
    {
        return $this->render('back/experience/index.html.twig', [
            'experiences' => $experienceRepository->findAll(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_back_experience_show", methods={"GET"})
     */
    public function show(Experience $experience): Response
    {
        return $this->render('back/experience/show.html.twig', [
            'experience' => $experience,
        ]);
    }

    /**
     * @Route("/{id}", name="app_back_experience_delete", methods={"POST"})
     */
    public function delete(Request $request, Experience $experience, ExperienceRepository $experienceRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$experience->getId(), $request->request->get('_token'))) {
            $experienceRepository->remove($experience, true);
        }

        return $this->redirectToRoute('app_back_experience_index', [], Response::HTTP_SEE_OTHER);
    }
}
