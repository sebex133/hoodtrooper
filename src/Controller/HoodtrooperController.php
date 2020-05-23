<?php

namespace App\Controller;

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
            'controller_name' => 'HoodtrooperController',
        ]);
    }
}
