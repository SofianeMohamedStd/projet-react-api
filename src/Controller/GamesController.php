<?php

namespace App\Controller;

use App\Entity\Categorie;
use App\Entity\Games;
use App\Entity\Studio;
use App\Repository\GamesRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class GamesController extends AbstractController
{
    /**
     * @Route("/games", name="games", methods={"GET"})
     */
    public function index(GamesRepository $gamesRepository)
    {
        $data = $gamesRepository->findAll();
        return $this->json($data);
    }

    /**
     * @param GamesRepository $gamesRepository
     * @param $id
     * @route("/game/{id}", name="game", methods={"GET"})
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     */
    public function gameOne(GamesRepository $gamesRepository,$id)
    {
        $data = $gamesRepository->find($id);
        return $this->json($data);
    }

    /**
     * @param Request $request
     * @param $id_s
     * @param $id_C
     * @return Response
     * @route("/game/ajout/{id_s}/{id_C}", name="ajout", methods={"POST"})
     */
    public function addGame(Request $request,$id_s,$id_C)
    {
        //if($request->isXmlHttpRequest()) {

            $game = new Games();

            // On décode les données envoyées
            $donnees = json_decode($request->getContent());

            // On hydrate l'objet
            $game->setTitre($donnees->titre);
            $game->setAnnee($donnees->annee);
            $game->setImage($donnees->image);
            $studio = $this->getDoctrine()->getRepository(Studio::class)->findOneBy(["id" => $id_s]);
            $game->setStudioId($studio);
            $categorie = $this->getDoctrine()->getRepository(Categorie::class)->findOneBy(["id" => $id_C]);
            $game->addIdCategorie($categorie);

            // On sauvegarde en base
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($game);
            $entityManager->flush();

            // On retourne la confirmation
            return new Response('ok', 201);
        //}
        //return new Response('Failed', 404);
    }

    /**
     * @param Games|null $game
     * @param Request $request
     * @Route("/game/editer/{id}", name="edit", methods={"PUT"})
     * @return Response
     */
    public function editGame(?Games $game, Request $request)
    {
        // On vérifie si la requête est une requête Ajax
        //if($request->isXmlHttpRequest()) {

            // On décode les données envoyées
            $donnees = json_decode($request->getContent());

            // On initialise le code de réponse
            $code = 200;

            // Si l'article n'est pas trouvé
            if(!$game){
                // On instancie un nouvel game
                $article = new Games();
                // On change le code de réponse
                $code = 201;
            }

            // On hydrate l'objet
            $game->setTitre($donnees->titre);
            $game->setAnnee($donnees->annee);
            $game->setImage($donnees->image);
            $studio = $this->getDoctrine()->getRepository(Studio::class)->find($donnees->studio);
            $game->setStudioId($studio);

            // On sauvegarde en base
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($game);
            $entityManager->flush();

            // On retourne la confirmation
            return new Response('ok', $code);
        //}
        //return new Response('Failed', 404);
    }


    /**
     * @Route("/game/supprimer/{id}", name="supprime",  requirements={"id":"\d+"}, methods={"DELETE"})
     * @param Games $games
     * @return Response
     */
    public function removeGame(Games $games)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($games);
        $entityManager->flush();
        return new Response('ok');
    }
}
