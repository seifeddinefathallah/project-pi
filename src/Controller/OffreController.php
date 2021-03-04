<?php

namespace App\Controller;

use App\Entity\Offre;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\{TextType,ButtonType,EmailType,HiddenType,PasswordType,TextareaType,SubmitType,NumberType,DateType,MoneyType,BirthdayType};

class OffreController extends AbstractController
{
    /**
     * @Route("/offre", name="offre")
     */
    public function index(): Response
    {
        return $this->render('offre/index.html.twig', [
            'controller_name' => 'OffreController',
        ]);
    }
    /**
     * @Route("/ajouterOffre", name="ajouterOffre")
     */
    public function ajouterOffre(Request $request){
        $offre = new Offre();
        $formu = $this->createFormBuilder ($offre)->add ('title',TextType::class)->add ('entreprise',TextType::class)->add ('type_contrat',TextType::class)->add ('location',TextType::class)->add ('description',TextType::class)->add('Ajouter',SubmitType::class ,['label'=> 'Ajouter'])->getForm();
        $formu ->handleRequest($request);
        if ($formu->isSubmitted()){
            $offre = $formu->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($offre);
            $em->flush();
            return $this->redirectToRoute('listoffre');
        }
        return $this->render('offre/ajouteroffre.html.twig' , ['form' => $formu->createView()

        ]);
    }
    /**
     * @Route("/listoffre",name="listoffre")
     */
    public function listoffre(){

        $listOffre=$this->getDoctrine()->getRepository(Offre::class)->findAll();

        return $this->render('offre/afficher.html.twig', [
            'listoffre' => $listOffre
        ]);

    }
    /**
     * @param $id
     * @Route("/supprimer/{id}",name="supprimer")
     */
    public function supprimer($id)
    {
        $em= $this->getDoctrine()->getManager();
        $offre=$em->getRepository( Offre::class)->find($id);
        $em->remove($offre);
        $em->flush();
        return $this->redirectToRoute( "listoffre");



    }
    /**
     * @Route("/modifieroffre/{id}", name="modifieroffre")
     */
    public function modifieroffre( Request $request , $id){
        $offre = $this->getDoctrine()->getRepository(Offre::class)->find($id); #}

        $form = $this->createFormBuilder ($offre)
            ->add ('title',TextType::class)
            ->add ('entreprise',TextType::class)
            ->add ('type_contrat',TextType::class)
            ->add ('location',TextType::class)
            ->add ('description',TextType::class)
            ->add('modifier',SubmitType::class ,['label'=> 'Modifier'])
            ->getForm();
        $form ->handleRequest($request);
        if ($form->isSubmitted()){
            $entity = $this->getDoctrine()->getManager();
            $entity->flush();
            return $this->redirectToRoute('listoffre');
        }
        return $this->render('offre/modifieroffre.html.twig' , [ 'form' => $form->createView()]);
    }

}
