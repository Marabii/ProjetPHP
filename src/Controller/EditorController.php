<?php

namespace App\Controller;

use App\Entity\Editor;
use App\Repository\EditorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/publisher')]
final class EditorController extends AbstractController
{
    #[Route('/', name: 'app_publisher_index')]
    public function index(EditorRepository $editorRepository): Response
    {
        $publishers = $editorRepository->findAll();

        return $this->render('publisher/index.html.twig', [
            'publishers' => $publishers,
        ]);
    }

    #[Route('/{id}', name: 'app_publisher_show', requirements: ['id' => '\d+'])]
    public function show(Editor $editor): Response
    {
        return $this->render('publisher/show.html.twig', [
            'publisher' => $editor,
        ]);
    }
}
