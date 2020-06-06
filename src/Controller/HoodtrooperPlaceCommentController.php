<?php

namespace App\Controller;

use App\Entity\HoodtrooperPlaceComment;
use App\Form\HoodtrooperPlaceCommentType;
use App\Repository\HoodtrooperPlaceCommentRepository;
use App\Repository\HoodtrooperPlaceRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/hoodtrooper/place/comment")
 */
class HoodtrooperPlaceCommentController extends AbstractController
{
    /**
     * @Route("/", name="hoodtrooper_place_comment_index", methods={"GET"})
     */
    public function index(HoodtrooperPlaceCommentRepository $hoodtrooperPlaceCommentRepository): Response
    {
        return $this->render('hoodtrooper_place_comment/index.html.twig', [
            'hoodtrooper_place_comments' => $hoodtrooperPlaceCommentRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="hoodtrooper_place_comment_new", methods={"GET","POST"})
     */
    public function new(Request $request, HoodtrooperPlaceCommentRepository $hoodtrooperPlaceCommentRepository, HoodtrooperPlaceRepository $hoodtrooperPlaceRepository): Response
    {
        //only logged in users
        $hoodtrooperUser = $this->getUser();

        if (!$hoodtrooperUser->getId()) {
            return $this->render('hoodtrooper_place/place_tooltip.html.twig', [
                'sign_in_title' => 'Sign in',
                'sign_up_title' => 'Sign up',
            ]);
        }

        $hoodtrooperPlaceComment = new HoodtrooperPlaceComment();
        $form = $this->createForm(HoodtrooperPlaceCommentType::class, $hoodtrooperPlaceComment);
        $form->handleRequest($request);

        //set user reference
        $hoodtrooperPlaceComment->setCommentAuthor($hoodtrooperUser);
        //set place reference
        $hoodtrooperPlace = $hoodtrooperPlaceRepository->findOneBy(['id' => $form->get('place_id_hidden')->getData()]);
        $hoodtrooperPlaceComment->setCommentRelatedPlace($hoodtrooperPlace);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($hoodtrooperPlaceComment);
            $entityManager->flush();

            //return all comments and new form for comment
            $hoodtrooperPlaceCommentFresh = new HoodtrooperPlaceComment();
            $formFresh = $this->createForm(HoodtrooperPlaceCommentType::class, $hoodtrooperPlaceCommentFresh);

            return $this->render('hoodtrooper_place/place_comments.html.twig', [
                'hoodtrooper_place_comments' => $hoodtrooperPlaceCommentRepository->findBy(['comment_related_place'=>$hoodtrooperPlace],['id'=>'DESC']),
                'is_author' => $hoodtrooperUser->getId() == $hoodtrooperPlace->getAuthor()->getId() ? true : false,
                'hoodtrooper_place_comment' => $hoodtrooperPlaceComment,
                'form' => $formFresh->createView(),
                'form_comment_action_url' => '/hoodtrooper/place/comment/new',
                'comment_author_id' => $hoodtrooperUser->getId(),
                'comment_place_id' => $hoodtrooperPlace->getId(),
            ]);
//            return $this->redirectToRoute('hoodtrooper');
        }

        return $this->render('hoodtrooper_place/place_comments.html.twig', [
            'hoodtrooper_place' => $hoodtrooperPlace,
            'hoodtrooper_place_comments' => $hoodtrooperPlaceCommentRepository->findBy(['comment_related_place'=>$hoodtrooperPlace],['id'=>'DESC']),
            'is_author' => $hoodtrooperUser->getId() == $hoodtrooperPlace->getAuthor()->getId() ? true : false,
            'hoodtrooper_place_comment' => $hoodtrooperPlaceComment,
            'form' => $form->createView(),
            'form_comment_action_url' => '/hoodtrooper/place/comment/new',
            'comment_author_id' => $hoodtrooperUser->getId(),
            'comment_place_id' => $hoodtrooperPlace->getId(),
        ]);
    }

    /**
     * @Route("/{id}", name="hoodtrooper_place_comment_show", methods={"GET"})
     */
    public function show(HoodtrooperPlaceComment $hoodtrooperPlaceComment): Response
    {
        return $this->render('hoodtrooper_place_comment/show.html.twig', [
            'hoodtrooper_place_comment' => $hoodtrooperPlaceComment,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="hoodtrooper_place_comment_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, HoodtrooperPlaceComment $hoodtrooperPlaceComment): Response
    {
        //only author of place
        $hoodtrooperUser = $this->getUser();

        if ($hoodtrooperPlaceComment->getCommentAuthor()->getId() != $hoodtrooperUser->getId()) {
            return $this->render('hoodtrooper_place/author_only.html.twig', [
                'sign_in_title' => 'Sign in',
                'sign_up_title' => 'Sign up',
            ]);
        }

        $form = $this->createForm(HoodtrooperPlaceCommentType::class, $hoodtrooperPlaceComment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('hoodtrooper');
        }

        return $this->render('hoodtrooper_place_comment/edit.html.twig', [
            'hoodtrooper_place_comment' => $hoodtrooperPlaceComment,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="hoodtrooper_place_comment_delete", methods={"DELETE"})
     */
    public function delete(Request $request, HoodtrooperPlaceComment $hoodtrooperPlaceComment, HoodtrooperPlaceCommentRepository $hoodtrooperPlaceCommentRepository, HoodtrooperPlaceRepository $hoodtrooperPlaceRepository): Response
    {
        //only author of place
        $hoodtrooperUser = $this->getUser();

        if ($hoodtrooperPlaceComment->getCommentAuthor()->getId() != $hoodtrooperUser->getId()) {
            return $this->render('hoodtrooper_place/author_only.html.twig', [
                'sign_in_title' => 'Sign in',
                'sign_up_title' => 'Sign up',
            ]);
        }

        if ($this->isCsrfTokenValid('delete'.$hoodtrooperPlaceComment->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($hoodtrooperPlaceComment);
            $entityManager->flush();

            //return all comments and new form for comment
            $hoodtrooperPlaceCommentFresh = new HoodtrooperPlaceComment();
            $formFresh = $this->createForm(HoodtrooperPlaceCommentType::class, $hoodtrooperPlaceCommentFresh);

            return $this->render('hoodtrooper_place/place_comments.html.twig', [
                'hoodtrooper_place_comments' => $hoodtrooperPlaceCommentRepository->findBy(['comment_related_place'=>$hoodtrooperPlaceComment->getCommentRelatedPlace()],['id'=>'DESC']),
                'is_author' => $hoodtrooperUser->getId() == $hoodtrooperPlaceComment->getCommentRelatedPlace()->getAuthor()->getId() ? true : false,
                'hoodtrooper_place_comment' => $hoodtrooperPlaceCommentFresh,
                'form' => $formFresh->createView(),
                'form_comment_action_url' => '/hoodtrooper/place/comment/new',
                'comment_author_id' => $hoodtrooperUser->getId(),
                'comment_place_id' => $hoodtrooperPlaceComment->getCommentRelatedPlace()->getId(),
            ]);
        }

        return $this->redirectToRoute('hoodtrooper');
    }
}
