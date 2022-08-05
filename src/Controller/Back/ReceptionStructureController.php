<?php

namespace App\Controller\Back;

use App\Entity\ReceptionStructure;
use App\Form\ReceptionStructureType;
use App\Repository\ReceptionStructureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

/**
 * @Route("/back/receptionStructure")
 */
class ReceptionStructureController extends AbstractController
{
    /**
     * @Route("/", name="app_back_reception_structure_index", methods={"GET"})
     */
    public function index(ReceptionStructureRepository $receptionStructureRepository): Response
    {
        return $this->render('back/reception_structure/index.html.twig', [
            'reception_structures' => $receptionStructureRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="app_back_reception_structure_new", methods={"GET", "POST"})
     */
    public function new(Request $request, ReceptionStructureRepository $receptionStructureRepository, SluggerInterface $slugger): Response
    {
        $receptionStructure = new ReceptionStructure();
        $form = $this->createForm(ReceptionStructureType::class, $receptionStructure);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $receptionStructure->setSlugName($slugger->slug($receptionStructure->getName())->lower());

            $receptionStructureRepository->add($receptionStructure, true);

            return $this->redirectToRoute('app_back_reception_structure_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/reception_structure/new.html.twig', [
            'reception_structure' => $receptionStructure,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_back_reception_structure_show", methods={"GET"})
     */
    public function show(ReceptionStructure $receptionStructure): Response
    {
        return $this->render('back/reception_structure/show.html.twig', [
            'reception_structure' => $receptionStructure,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_back_reception_structure_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, ReceptionStructure $receptionStructure, ReceptionStructureRepository $receptionStructureRepository, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(ReceptionStructureType::class, $receptionStructure);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $receptionStructure->setSlugName($slugger->slug($receptionStructure->getName())->lower());

            $receptionStructureRepository->add($receptionStructure, true);

            return $this->redirectToRoute('app_back_reception_structure_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('back/reception_structure/edit.html.twig', [
            'reception_structure' => $receptionStructure,
            'form' => $form,
        ]);
    }

    /**
     * @Route("/{id}", name="app_back_reception_structure_delete", methods={"POST"})
     */
    public function delete(Request $request, ReceptionStructure $receptionStructure, ReceptionStructureRepository $receptionStructureRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$receptionStructure->getId(), $request->request->get('_token'))) {
            $receptionStructureRepository->remove($receptionStructure, true);
        }

        return $this->redirectToRoute('app_back_reception_structure_index', [], Response::HTTP_SEE_OTHER);
    }
}
