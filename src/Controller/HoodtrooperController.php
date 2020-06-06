<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HoodtrooperController extends AbstractController
{
    /**
     * @Route("/", name="hoodtrooper")
     */
    public function index()
    {
        return $this->render('hoodtrooper/index.html.twig', [
            'sign_in_title' => 'Sign in',
            'sign_up_title' => 'Sign up',
            'show_all_places_title' => 'All places',
            'add_new_place_title' => 'Add new place',
            'controller_name' => 'HoodtrooperController',
        ]);
    }

    /**
     * @Route("/hoodtrooper/place_tooltip", name="hoodtrooper_place_tooltip", methods={"GET","POST"})
     */
    public function place_tooltip(Request $request): Response
    {
        $lat = $request->query->get('lat');
        $lng = $request->query->get('lng');

        return $this->render('hoodtrooper_place/place_tooltip.html.twig', [
            'sign_in_title' => 'Sign in',
            'sign_up_title' => 'Sign up',
            'show_all_places_title' => 'All places',
            'add_new_place_title' => 'Add new place',
            'lat' => $lat ? $lat : '',
            'lng' => $lng ? $lng : '',
        ]);
    }
}
