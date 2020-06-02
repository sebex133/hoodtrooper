<?php

namespace App\Controller;

use App\Entity\HoodtrooperPlace;
use App\Form\HoodtrooperPlaceType;
use App\Repository\HoodtrooperPlaceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\String\Slugger\SluggerInterface;

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
            'hoodtrooper_place_images_directory' => $this->getParameter('hoodtrooper_place_images_directory'),
        ]);
    }

    /**
     * @Route("/places_json", name="hoodtrooper_places_json", methods={"GET"})
     */
    public function places_json(HoodtrooperPlaceRepository $hoodtrooperPlaceRepository): Response
    {
        $places =  $hoodtrooperPlaceRepository->findAll();
        $places_json = [];

        foreach ($places as $item){
            $places_json[] = [
                'position' => [
                    'lat' => (float) $item->getCoordinateLat(),
                    'lng' => (float) $item->getCoordinateLng(),
                ],
                'id' => (string) $item->getId(),
                'filled' => true,
                'color' => "#6A1E74",
                "title" => $item->getTitle(),
                "linkLabel" => $item->getTitle() . ' - show',
                "linkDirection" => '/hoodtrooper/place/' . $item->getId() . '/show',
                'tooltip' => [
                    'items' => [
                        [
                            'label' => 'Description',
                            'content' => $item->getDescription(),
                        ],
                        [
                            'label' => 'Filename',
                            'content' => $item->getPlaceImageFilename(),
                        ],
                    ],
                ],
            ];
        }
//
//        print '<pre>';
//        var_dump($places_json);
//        die();

        return $this->json(['places_json' => $places_json]);
    }

    /**
     * @Route("/new", name="hoodtrooper_place_new", methods={"GET","POST"})
     */
    public function new(Request $request, SluggerInterface $slugger): Response
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
            $imageFile = $form->get('image')->getData();

            if($imageFile){
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $imageFile->move(
                        $this->getParameter('hoodtrooper_place_images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $hoodtrooperPlace->setPlaceImageFilename($newFilename);
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($hoodtrooperPlace);
            $entityManager->flush();

            return $this->json(['success_ajax_form' => TRUE]);
//            return $this->redirectToRoute('hoodtrooper_place_index');
        }

        $lat = $request->query->get('lat');
        $lng = $request->query->get('lng');

        return $this->render('hoodtrooper_place/new.html.twig', [
            'hoodtrooper_place' => $hoodtrooperPlace,
            'form' => $form->createView(),
//            'form_action_url' =>$request->get('_route'),
            'form_action_url' => $request->getRequestUri(),
            'lat' => $lat ? $lat : '',
            'lng' => $lng ? $lng : '',
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
    public function edit(Request $request, HoodtrooperPlace $hoodtrooperPlace, SluggerInterface $slugger): Response
    {
        $form = $this->createForm(HoodtrooperPlaceType::class, $hoodtrooperPlace);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $imageFile = $form->get('image')->getData();

            if($imageFile){
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $imageFile->move(
                        $this->getParameter('hoodtrooper_place_images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $hoodtrooperPlace->setPlaceImageFilename($newFilename);
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('hoodtrooper_place_index');
        }

        $lat = $request->query->get('lat');
        $lng = $request->query->get('lng');

        return $this->render('hoodtrooper_place/edit.html.twig', [
            'hoodtrooper_place' => $hoodtrooperPlace,
            'form' => $form->createView(),
//            'form_action_url' => $request->get('_route'),
            'form_action_url' => $request->getRequestUri(),
            'lat' => $lat ? $lat : '',
            'lng' => $lng ? $lng : '',
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
