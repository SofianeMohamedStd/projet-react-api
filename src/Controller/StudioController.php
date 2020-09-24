<?php

namespace App\Controller;

use App\Repository\StudioRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class StudioController extends AbstractController
{
    /**
     * @Route("/studio", name="studio", methods={"GET"})
     * @param StudioRepository $studioRepository
     * @return JsonResponse
     */
    public function index(StudioRepository $studioRepository)
    {
        $data = $studioRepository->findAll();
        return $this->json($data);
    }
}
