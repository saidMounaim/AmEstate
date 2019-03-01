<?php

namespace App\Controller;

use App\Entity\Property;
use App\Form\PropertyType;
use App\Repository\PropertyRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Knp\Component\Pager\PaginatorInterface;

class PropertyController extends AbstractController
{
    /**
     * @Route("/property", name="property_list")
     */
    public function index(PropertyRepository $repo, Request $request, PaginatorInterface $paginator)
    {
        $query = $repo->getAll();
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            6/*limit per page*/
        );
        return $this->render('property/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * @Route("/property/create", name="property_create")
     * @IsGranted("ROLE_USER")
     */
    public function create(Request $request, ObjectManager $manager)
    {
        $property = new Property();
        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            // Add Images Properties
            foreach ($property->getImages() as $image)
            {
                $image->setProperty($property);
                $manager->persist($image);
            }

            $property->setAgent($this->getUser());
            $manager->persist($property);
            $manager->flush();

            return $this->redirectToRoute('property_show', [
                'slug' => $property->getSlug()
            ]);

        }

        return $this->render('property/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/property/edit/{slug}", name="property_edit")
     * @Security("is_granted('ROLE_USER') and property.getAuthor()")
     */
    public function edit(Property $property, Request $request, ObjectManager $manager)
    {
        $form = $this->createForm(PropertyType::class, $property);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Add Images Properties
            foreach ($property->getImages() as $image) {
                $image->setProperty($property);
                $manager->persist($image);
            }

            $property->setAgent($this->getUser());
            $manager->persist($property);
            $manager->flush();

            return $this->redirectToRoute('property_show', [
                'slug' => $property->getSlug()
            ]);

        }

        return $this->render('property/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/property/delete/{id}", name="property_delete")
     * @Security("is_granted('ROLE_USER') and property.getAuthor()")
     */
    public function delete(Property $property, ObjectManager $manager)
    {
        $manager->remove($property);
        $manager->flush();
        $response = new Response();
        return $response->send();
    }

    /**
     * @Route("/property/{slug}", name="property_show")
     */
    public function show(Property $property)
    {
        return $this->render('property/show.html.twig', [
            'property' => $property
        ]);
    }


}
