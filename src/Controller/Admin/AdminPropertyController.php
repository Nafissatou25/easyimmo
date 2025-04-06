<?php
namespace App\Controller\Admin;

use App\Entity\Property;
use App\Form\PropertyType;
use App\Repository\PropertyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistance\ObjectManager;
use Symfony\Component\Routing\Annotation\Route; 
//use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Console\Helper\Helper;
use Symfony\Component\HttpFoundation\Response;  //remplacé par @return \Symfony\Component\HttpFoundation\Response
//use Vich\UploaderBundle\Templating\Helper\UploaderHelper;

class AdminPropertyController extends AbstractController
{
    /**
     * @var PropertyRepository
     */
    private $repository;
    /**
     * @var EntityManagerInterface;
     */
    private $em;

    public function __construct(PropertyRepository $repository, EntityManagerInterface $em)
    {    
        $this->repository = $repository;
        $this->em = $em; // ajouté dans le constructeur pour gerer l'edition
    }

    /**
     * @Route("/admin", name="admin.property.index")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function index()
    {
       $properties = $this->repository->findAll();
       return $this->render('admin/index.html.twig', compact('properties'));
    }

    /**
     * @Route("/admin/property/create", name="admin.property.new")
     */
    public function new(Request $request)
    {

        $property = new Property;
        $form = $this->createForm(PropertyType::class, $property); 
        $form->handleRequest($request); 
        
        if ($form->isSubmitted() && $form->isValid()) { 
            $this->em->persist($property);
            $this->em->flush();
            $this->addFlash('success', 'Bien créé avec succès');
            return $this->redirectToRoute('admin.property.index');
        }
        return $this->render('admin/new.html.twig', [
            'property' => $property,
            'form' => $form->createView()
        ]);




    } 



    /**
     * @Route("/admin/property/{id}", name="admin.property.edit", methods="GET|POST")
     * @param Property $property
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit(Property $property, Request $request)
    {
        $form = $this->createForm(PropertyType::class, $property); //creation du formulaire au prealable taper php bin/console make:form 
        $form->handleRequest($request); //utilisé pour recuperer les elmt du form gerer la requete
        
        
        if ($form->isSubmitted() && $form->isValid()) {  
                    // pour verifier si la nouvell image a été uploadé et supprimer limage au niveau ducach.
                    // pour ce fair on avai injecté  CacheManager $cacheManager, UploaderHelper $helper dans edit
                        /*if($property->getImageFile() instanceof UploadedFile) {
                            $cacheManager->remove($helper->asset($property, 'imageFile'));
                        }*/

            $this->em->flush();
            $this->addFlash('success', 'Bien modifié avec succès');
            return $this->redirectToRoute('admin.property.index');

        }
        return $this->render('admin/edit.html.twig', [
            'property' => $property,
            'form' => $form->createView()
        ]); // compact('property') property coe tableau mais remplacé dans le cadr d la creation du formulair
    }

    /**
     * @Route("/admin/property/{id}", name="admin.property.delete", methods="DELETE")
     * @param Property $property
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete(Property $property, Request $request)
    {
        if ($this->isCsrfTokenValid('delete' . $property->getId(), $request->get('_token'))) {
            $this->em->remove($property);
            $this->em->flush();
            $this->addFlash('success', 'Bien supprimé avec succès');
        }
        return $this->redirectToRoute('admin.property.index');
    }
}