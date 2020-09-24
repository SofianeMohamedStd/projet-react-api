<?php

namespace App\Controller;

use App\Repository\CommentsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class CategorieController extends AbstractController
{
    /**
     * @Route("/categorie", name="categorie")
     * @param CommentsRepository $commentsRepository
     * @return JsonResponse
     */
    public function index(CommentsRepository $commentsRepository)
    {
        $data = $commentsRepository->findAll();
        return $this->json($data);

    }
}
