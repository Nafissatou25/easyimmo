<?php
namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Property;
use App\Form\ContactType;
use App\Entity\PropertySearch;
use App\Form\PropertySearchType;
use App\Notification\ContactNotification;
use Symfony\Component\Form\Form;
//use Symfony\Component\Form\FormTypeInterface;
use App\Repository\PropertyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;



class PropertyController extends AbstractController
{
    /**
     * @var PropertyRepository
     */
    private $repository; 
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(PropertyRepository $repository, EntityManagerInterface $em)
    {
        $this->repository = $repository;
        $this->em = $em;

    }

    /**
     * @Route("/biens", name="property.index")
     * @return Response
     */
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        

                                                                      /*pour recuperer ds bd mthod1 $repository = $this->getDoctrine()->getRepository(Property::class);
                                                                                //dump($repository);                                                   dump($repository);  

                                                                    $repository = $this->repository->findAllVisible();         
                                                                    $property[0]->setSold(true);
                                                                    $this->em->flush();*/

          // pour faire la recherch il faut créer une entité non reliée à la bd sans passser par l'invite de command qui va gerer la recher
          //créer un formulair sur invit de command php bin/console make: form on lui donne un nom apres on l'indiq \App\Entity\PropertySearch
          //gerer le traitement ds le controller                                                          

        $search = new PropertySearch();
        $form = $this->createForm(PropertySearchType::class, $search);
        $form->handleRequest($request);

        $properties = $paginator->paginate(
            $this->repository->findAllVisibleQuery($search),
            $request->query->getInt('page', 1), /*page number*/
            10  /*limit per page*/  
    );  //$properties = $this->repository->findAllVisible();  remplacé pour la pagination en faisant linjection dans index() un ficher knp_paginator.yml a été crée ds ce sens dans config/pakage  où on a inséré tout un bloq d'instruction                                                     
        return $this->render('property/index.html.twig', [
         'current_menu' => 'properties',
         'properties'   => $properties,
        'form' => $form->createView()]);           //return new Response('les biens');  //, ['current_menu' => 'properties'] ajouté pour faire activer le bouton acheter aptres clic. le nom du controleur current_menu peut etr remplacé par nimporte quoi .la suite se fait sur le bouton achter dans base.html.twig
    }

    /**
     * @Route("/biens/{slug}-{id}", name="property.show", requirements={"slug": "[a-z0-9\-]*"})
     *@param Property @property
     *@return Response
     */
    public function show(Property $property, string $slug, Request $request, ContactNotification $notification): Response 
    {
        // la classe contactnotification a été crée pour effectuer le contact 
        if ($property->getSlug() !== $slug){
            return $this->redirectToRoute('property.show', [
                'id' => $property->getId(),
                'slug' => $property->getSlug()
            ], 301);
        }
        
        $contact = new Contact();
        $contact->setProperty($property);
        $form = $this->createForm( ContactType::class, $contact);
        $form->handleRequest($request);// auu niveau du form gere la requte
        if ($form->isSubmitted() && $form->isValid()){
            $notification->notify($contact);
            $this->addFlash('success','votre email a été bien envoyé');
            return $this->redirectToRoute('property.show', [
                'id' => $property->getId(),
                'slug' => $property->getSlug()
            ]);
        }

        /*public function show($slug, $id): Response
        $property= $this->repository->find($id); remplacé par linjection on obtient aussi le mem resutat
        et pas besoin de recuperer l'id car il le fera grace à la route où l'id est deja defini. l'injection en question c'est public function show(Property $property) : response
        la re
        */
        return $this->render('property/show.html.twig',[
            'property'       => $property,
            'current_menu'   => 'properties',
            'form'           => $form->createView()]);
    }
}
/*$property = new Property();
        $property->setTitle('Mon premier bien') 
                 ->setPrice(200000)
                 ->setRooms(4)
                 ->setBedrooms(4)
                 ->setDescription('une petite description')   
                 ->setSurface(60)
                 ->setFloor(4)
                 ->setHeat(1)  
                 ->setCity('Monpellier')
                 ->setAddress('15 Boulevard Gambetta')
                 ->setPostalCode('34000')
                 ->setEfface('moto');  
            $em = $this->getDoctrine()->getManager();
            $em->persist($property);
            $em->flush();*/