<?php

namespace App\Controller;

use App\Entity\Coaster;
use App\Form\CoasterType;
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

            return $this->redirectToRoute('app_coaster_add');
        }
 

        return $this->render('coaster/add.html.twig', ['coasterForm' => $form,]);
    }
}