<?php

namespace App\Controller;

use App\Entity\Coaster;
use App\Form\CoasterType;
use App\Repository\CoasterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CoasterController extends AbstractController
{
    #[Route('/coaster/add')]
    public function add(Request $request, EntityManagerInterface $em): Response
    {
        //création d'entité
        $entity = new Coaster();
        $form = $this->createForm(CoasterType::class, $entity);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($entity);//Ajoute l'entité dans le manager
            $em->flush();//exécute les requetes

            return $this->redirectToRoute('app_base_home');
        }
 

        return $this->render('coaster/add.html.twig', ['coasterForm' => $form,]);
    }
    
    #[Route('/coaster/index')]
    public function index(CoasterRepository $repository): Response
    {
        $coasters = $repository->findAll();

        return $this->render('coaster/index.html.twig', ['coasters' => $coasters]);
    }

    #[Route('/coaster/{id}/edit')]
    public function edit(Coaster $entity, Request $request, EntityManagerInterface $em)
    {
       
        $form = $this->createForm(CoasterType::class, $entity);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            
            $em->flush();//exécute les requetes

            return $this->redirectToRoute('app_coaster_index');
        }
        return $this->render('coaster/edit.html.twig', ['coasterForm' => $form,]);
    }

    #[Route('/coaster/{id}/delete')]
    public function delete(Coaster $coaster, Request $request, EntityManagerInterface $em): Response
    {
        if ($request->isMethod('POST')) { 
            if ($this->isCsrfTokenValid(
                'delete'.$coaster->getId(),
                $request->request->get('_token')
            )) {
                $em->remove($coaster);
                $em->flush();

                return $this->redirectToRoute('app_coaster_index');
            }
        }
        return $this->render('coaster/delete.html.twig', ['coaster' => $coaster,]);
        
    }
}