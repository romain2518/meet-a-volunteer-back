<?php

namespace App\Controller\Back;

use App\Entity\Thematic;
use App\Form\ThematicType;
use App\Repository\ThematicRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/back/thematic")
 */
class ThematicController extends AbstractController
{
    /**
     * @Route("/", name="app_back_thematic_index", methods={"GET"})
     */
    public function index(ThematicRepository $thematicRepository): Response
    {
        return $this->render('back/thematic/index.html.twig', [
            'thematics' => $thematicRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_back_thematic_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ThematicRepository $thematicRepository): Response
    {
        $thematic = new Thematic();
        $form = $this->createForm(ThematicType::class, $thematic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $thematicRepository->add($thematic, true);

            return $this->redirectToRoute('app_back_thematic_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/thematic/new.html.twig', [
            'thematic' => $thematic,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_back_thematic_show", methods={"GET"})
     */
    public function show(Thematic $thematic): Response
    {
        return $this->render('back/thematic/show.html.twig', [
            'thematic' => $thematic,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_back_thematic_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Thematic $thematic, ThematicRepository $thematicRepository): Response
    {
        $form = $this->createForm(ThematicType::class, $thematic);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $thematicRepository->add($thematic, true);

            return $this->redirectToRoute('app_back_thematic_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/thematic/edit.html.twig', [
            'thematic' => $thematic,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_back_thematic_delete", methods={"POST"})
     */
    public function delete(Request $request, Thematic $thematic, ThematicRepository $thematicRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$thematic->getId(), $request->request->get('_token'))) {
            $thematicRepository->remove($thematic, true);
        }

        return $this->redirectToRoute('app_back_thematic_index', [], Response::HTTP_SEE_OTHER);
    }
}
