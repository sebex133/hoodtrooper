<?php

namespace App\Controller;

use App\Entity\HoodtrooperPlace;
use App\Form\HoodtrooperPlaceType;
use App\Repository\HoodtrooperPlaceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/hoodtrooper/place")
 */
class HoodtrooperPlaceController extends AbstractController
{
    /**
     * @Route("/", name="hoodtrooper_place_index", methods={"GET"})
     */
    public function index(HoodtrooperPlaceRepository $hoodtrooperPlaceRepository): Response
    {
        return $this->render('hoodtrooper_place/index.html.twig', [
            'hoodtrooper_places' => $hoodtrooperPlaceRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="hoodtrooper_place_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        //only logged in users
        if (!$this->getUser()) {
            return $this->render('hoodtrooper_place/place_tooltip.html.twig', [
                'sign_in_title' => 'Sign in',
                'sign_up_title' => 'Sign up',
            ]);
        }

        $hoodtrooperPlace = new HoodtrooperPlace();
        $form = $this->createForm(HoodtrooperPlaceType::class, $hoodtrooperPlace);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($hoodtrooperPlace);
            $entityManager->flush();

            return $this->json(['success_ajax_form' => TRUE]);
//            return $this->redirectToRoute('hoodtrooper_place_index');
        }

        $latLng = $request->query->get('latlng_from_map');

        return $this->render('hoodtrooper_place/new.html.twig', [
            'hoodtrooper_place' => $hoodtrooperPlace,
            'form' => $form->createView(),
            'latLng' => $latLng ? $latLng : '',
        ]);
    }

    /**
     * @Route("/{id}", name="hoodtrooper_place_show", methods={"GET"})
     */
    public function show(HoodtrooperPlace $hoodtrooperPlace): Response
    {
        return $this->render('hoodtrooper_place/show.html.twig', [
            'hoodtrooper_place' => $hoodtrooperPlace,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="hoodtrooper_place_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, HoodtrooperPlace $hoodtrooperPlace): Response
    {
        $form = $this->createForm(HoodtrooperPlaceType::class, $hoodtrooperPlace);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('hoodtrooper_place_index');
        }

        return $this->render('hoodtrooper_place/edit.html.twig', [
            'hoodtrooper_place' => $hoodtrooperPlace,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="hoodtrooper_place_delete", methods={"DELETE"})
     */
    public function delete(Request $request, HoodtrooperPlace $hoodtrooperPlace): Response
    {
        if ($this->isCsrfTokenValid('delete'.$hoodtrooperPlace->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($hoodtrooperPlace);
            $entityManager->flush();
        }

        return $this->redirectToRoute('hoodtrooper_place_index');
    }
}
