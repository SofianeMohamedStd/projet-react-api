<?php

namespace App\Controller;


use App\Entity\Comments;
use App\Entity\Games;
use App\Repository\CommentsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentsController extends AbstractController
{
    /**
     * @Route("/gameC", name="comments", methods={"GET"})
     * @param CommentsRepository $commentsRepository
     * @return JsonResponse
     */
    public function getComment(CommentsRepository $commentsRepository)
    {
        $data = $commentsRepository->findAll();
        return $this->json($data);

    }

    /**
     * @param Request $request
     * @param $id
     * @return Response
     * @route("/game/ajoutC/{id}", name="AjoutComment", methods={"POST"})
     */
    public function addComment(Request $request, $id)
    {
        //if($request->isXmlHttpRequest()) {

        $comment = new Comments();

        // On décode les données envoyées
        $donnees = json_decode($request->getContent());

        // On hydrate l'objet
        $comment->setNom($donnees->nom);
        $comment->setMessage($donnees->message);
        $game = $this->getDoctrine()->getRepository(Games::class)->findOneBy(["id" => $id]);
        $comment ->setGameId($game);


        // On sauvegarde en base
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($comment);
        $entityManager->flush();

        // On retourne la confirmation
        return new Response('ok', 201);
        // }
        // return new Response('Failed', 404);
    }
    public function editComment(?Comments $comment, Request $request)
    {
        // On vérifie si la requête est une requête Ajax
        //if($request->isXmlHttpRequest()) {

        // On décode les données envoyées
        $donnees = json_decode($request->getContent());
        $code = 200;

        if(!$comment){
            $comment = new Comments();
            $code = 201;
        }

        // On hydrate l'objet
        $comment->setNom($donnees->nom);
        $comment->setMessage($donnees->message);


        // On sauvegarde en base
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($comment);
        $entityManager->flush();

        // On retourne la confirmation
        return new Response('ok', $code);
        //}
        //return new Response('Failed', 404);
    }

    /**
     * @param Comments $comments
     * @Route("/comment/supprimer/{id}", name="supprime", methods={"DELETE"})
     * @return Response
     */
    public function removeComment(Comments $comments)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($comments);
        $entityManager->flush();
        return new Response('ok');
    }
}
