<?php

namespace App\Controller;

use App\Repository\BookRepository;
use App\Repository\AuthorRepository;
use App\Repository\EditorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class MainController extends AbstractController
{
    #[Route('/', name: 'app_main')]
    public function index(
        BookRepository $bookRepository,
        AuthorRepository $authorRepository,
        EditorRepository $editorRepository
    ): Response
    {
        // Get some statistics for the homepage
        $totalBooks = $bookRepository->count([]);
        $totalAuthors = $authorRepository->count([]);
        $totalEditors = $editorRepository->count([]);

        // Get recent books (limit to 6 for display)
        $recentBooks = $bookRepository->findBy([], ['editedAt' => 'DESC'], 6);

        return $this->render('main/index.html.twig', [
            'total_books' => $totalBooks,
            'total_authors' => $totalAuthors,
            'total_editors' => $totalEditors,
            'recent_books' => $recentBooks,
        ]);
    }
}
