<?php

namespace App\Controller;

use App\Entity\Wish;
use App\Form\WishType;
use App\Repository\CategoryRepository;
use App\Repository\WishRepository;
use App\Util\Censurator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/wish', name: 'wish_')]
class WishController extends AbstractController
{
    public function __construct(){}

    #[Route('/list', name: 'list')]
    public function listWish(WishRepository $wishRepository): Response
    {
        $wishes = $wishRepository->findBy(["isPublished" => true]);

        return $this->render('wish/list.html.twig', [
            'wishes' => $wishes,
        ]);
    }

    #[Route('/{wishId}/details', name: 'details')]
    public function detailsWish(WishRepository $wishRepository,int $wishId): Response
    {
        $wish = $wishRepository->find($wishId);

        return $this->render('wish/details.html.twig', [
            "wish" => $wish,
        ]);
    }
    #[Route('/remove/{wishId}', name: 'remove')]
    public function removeWish(WishRepository $wishRepository,int $wishId): Response
    {
        $wishRepository->remove($wishRepository->find($wishId), true);
        return $this->redirectToRoute('wish_details');
    }

    #[Route('/create', name: 'create')]
    public function create(EntityManagerInterface $anEntity, Request $request, Censurator $censurator, CategoryRepository $categoryRepository){
        $wish = new Wish("","",$this->getUser()->getLogin(),true);
        $wishForm = $this->createForm(WishType::class, $wish);
        $wishForm->handleRequest($request);
        if($wishForm->isSubmitted() && $wishForm->isValid())
        {
            $wish = new Wish(
                $wishForm->get('title')->getData(),
                $censurator->purify($wishForm->get('description')->getData()),
                $wishForm->get('author')->getData(),
                true);
                $wish->setCategory($categoryRepository->find($wishForm->get('category')->getData()));
            $anEntity->persist($wish);
            $anEntity->flush();
            $this->addFlash('success','Wish created !');
            return $this->redirectToRoute('wish_details', ["wishId"=>$wish->getId()]);
        }

        return $this->render('wish/create.html.twig', [
            'wishForm' => $wishForm->createView(),
        ]);
    }
}
