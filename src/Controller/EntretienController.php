<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\{TextType,ButtonType,EmailType,HiddenType,PasswordType,TextareaType,SubmitType,NumberType,DateType,MoneyType,BirthdayType};
use App\Entity\Entretien;

class EntretienController extends AbstractController
{
    /**
     * @Route("/entretien", name="entretien")
     */
    public function index(): Response
    {
        return $this->render('entretien/index.html.twig', [
            'controller_name' => 'EntretienController',
        ]);
    }
    /**
     * @Route("/ajouterEntretien", name="ajouterEntretien")
     */
    public function ajouterEntretien(Request $request){
        $entretien = new Entretien();
        $formu = $this->createFormBuilder ($entretien)->add ('OI',TextType::class)->add ('type',TextType::class)->add ('adresse',TextType::class)->add ('date',DateType::class)->add('Ajouter',SubmitType::class ,['label'=> 'Ajouter'])->getForm();
        $formu ->handleRequest($request);
        if ($formu->isSubmitted()){
            $entretien = $formu->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($entretien);
            $em->flush();
            return $this->redirectToRoute('listentretien');
        }
        return $this->render('entretien/ajouterentretien.html.twig' , ['form' => $formu->createView()

        ]);
    }
    /**
     * @Route("/listentretien",name="listentretien")
     */
    public function listentretien(){

        $listEntretien=$this->getDoctrine()->getRepository(Entretien::class)->findAll();

        return $this->render('entretien/afficher.html.twig', [
            'listentretien' => $listEntretien
        ]);

    }
    /**
     * @param $id
     * @Route("/supprimer/{id}",name="supprimer")
     */
    public function supprimer($id)
    {
        $em= $this->getDoctrine()->getManager();
        $entretien=$em->getRepository( Entretien::class)->find($id);
        $em->remove($entretien);
        $em->flush();
        return $this->redirectToRoute( "listentretien");



    }
    /**
     * @Route("/modifierentretien/{id}", name="modifierentretien")
     */
    public function modifierentretien( Request $request , $id){
        $entretien = $this->getDoctrine()->getRepository(Entretien::class)->find($id); #}

        $form = $this->createFormBuilder ($entretien)
            ->add ('OI',TextType::class)
            ->add ('type',TextType::class)
            ->add ('adresse',TextType::class)
            ->add ('date',DateType::class)

            ->add('modifier',SubmitType::class ,['label'=> 'Modifier'])
            ->getForm();
        $form ->handleRequest($request);
        if ($form->isSubmitted()){
            $entity = $this->getDoctrine()->getManager();
            $entity->flush();
            return $this->redirectToRoute('listentretien');
        }
        return $this->render('entretien/modifierentretien.html.twig' , [ 'form' => $form->createView()]);
    }
}
