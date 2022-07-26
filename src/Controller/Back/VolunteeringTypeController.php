<?php

namespace App\Controller\Back;

use App\Entity\VolunteeringType;
use App\Form\VolunteeringTypeType;
use App\Repository\VolunteeringTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/back/volunteeringType")
 */
class VolunteeringTypeController extends AbstractController
{
    /**
     * @Route("/", name="app_back_volunteering_type_index", methods={"GET"})
     */
    public function index(VolunteeringTypeRepository $volunteeringTypeRepository): Response
    {
        return $this->render('back/volunteering_type/index.html.twig', [
            'volunteering_types' => $volunteeringTypeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_back_volunteering_type_new", methods={"GET", "POST"})
     */
    public function new(Request $request, VolunteeringTypeRepository $volunteeringTypeRepository): Response
    {
        $volunteeringType = new VolunteeringType();
        $form = $this->createForm(VolunteeringTypeType::class, $volunteeringType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $volunteeringTypeRepository->add($volunteeringType, true);

            return $this->redirectToRoute('app_back_volunteering_type_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/volunteering_type/new.html.twig', [
            'volunteering_type' => $volunteeringType,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_back_volunteering_type_show", methods={"GET"})
     */
    public function show(VolunteeringType $volunteeringType): Response
    {
        return $this->render('back/volunteering_type/show.html.twig', [
            'volunteering_type' => $volunteeringType,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_back_volunteering_type_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, VolunteeringType $volunteeringType, VolunteeringTypeRepository $volunteeringTypeRepository): Response
    {
        $form = $this->createForm(VolunteeringTypeType::class, $volunteeringType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $volunteeringTypeRepository->add($volunteeringType, true);

            return $this->redirectToRoute('app_back_volunteering_type_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/volunteering_type/edit.html.twig', [
            'volunteering_type' => $volunteeringType,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_back_volunteering_type_delete", methods={"POST"})
     */
    public function delete(Request $request, VolunteeringType $volunteeringType, VolunteeringTypeRepository $volunteeringTypeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$volunteeringType->getId(), $request->request->get('_token'))) {
            $volunteeringTypeRepository->remove($volunteeringType, true);
        }

        return $this->redirectToRoute('app_back_volunteering_type_index', [], Response::HTTP_SEE_OTHER);
    }
}
