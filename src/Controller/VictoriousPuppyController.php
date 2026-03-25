<?php

namespace App\Controller;

use App\Entity\VictoriousPuppy;
use App\Form\VictoriousPuppyType;
use App\Repository\VictoriousPuppyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/victorious/puppy')]
final class VictoriousPuppyController extends AbstractController
{
    #[Route(name: 'app_victorious_puppy_index', methods: ['GET'])]
    public function index(VictoriousPuppyRepository $victoriousPuppyRepository): Response
    {
        return $this->render('victorious_puppy/index.html.twig', [
            'victorious_puppies' => $victoriousPuppyRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_victorious_puppy_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $victoriousPuppy = new VictoriousPuppy();
        $form = $this->createForm(VictoriousPuppyType::class, $victoriousPuppy);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($victoriousPuppy);
            $entityManager->flush();

            return $this->redirectToRoute('app_victorious_puppy_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('victorious_puppy/new.html.twig', [
            'victorious_puppy' => $victoriousPuppy,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_victorious_puppy_show', methods: ['GET'])]
    public function show(VictoriousPuppy $victoriousPuppy): Response
    {
        return $this->render('victorious_puppy/show.html.twig', [
            'victorious_puppy' => $victoriousPuppy,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_victorious_puppy_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, VictoriousPuppy $victoriousPuppy, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(VictoriousPuppyType::class, $victoriousPuppy);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_victorious_puppy_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('victorious_puppy/edit.html.twig', [
            'victorious_puppy' => $victoriousPuppy,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_victorious_puppy_delete', methods: ['POST'])]
    public function delete(Request $request, VictoriousPuppy $victoriousPuppy, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$victoriousPuppy->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($victoriousPuppy);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_victorious_puppy_index', [], Response::HTTP_SEE_OTHER);
    }
}
