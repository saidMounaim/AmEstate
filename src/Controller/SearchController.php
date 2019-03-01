<?php

namespace App\Controller;

use App\Entity\PropertySearch;
use App\Form\PropertySearchType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\PropertyRepository;

class SearchController extends AbstractController
{

    public function search(Request $request, PropertyRepository $repo)
    {
        $propertySearch = new PropertySearch();
        $form = $this->createForm(PropertySearchType::class, $propertySearch, [
            'action' => $this->generateUrl('property_search'),
            'method' => 'POST',
        ]);
        $form->handleRequest($request);

        return $this->render('search/searchBar.html.twig', [
            'search' => $form->createView()
        ]);

    }


}
