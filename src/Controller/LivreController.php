<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Emprunt;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
/**
 * @Route("member/livre")
 */
class LivreController extends AbstractController
{
    /**
     * @Route("/", name="livre")
     */
    public function index(BookRepository $BookRepository): Response
    {
        $books = $BookRepository->findActiveBook();
        return $this->render('livre/index.html.twig', [
            'books' => $books,
        ]);
    }

    /**
     * @Route("/emprunter/{id}", name="livre_emprunter")
     */
    public function emprunter(Book $book, EntityManagerInterface $entityManager): Response
    {
        $user=$this->getUser();
        $emprunt = new Emprunt();
        $emprunt->setUser($user)->setLivre($book);
        $entityManager->persist($emprunt);
        $entityManager->flush();
        return $this->redirectToRoute('livre');
    }

    /**
     * @Route("/retourner/{id}", name="livre_retourner")
     */
    public function retourner(Emprunt $emprunt, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($emprunt);
        $entityManager->flush();
        return $this->redirectToRoute('livre');
    }
}
