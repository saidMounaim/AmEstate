<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\PropertyRepository;
use App\Repository\AgentRepository;
use Doctrine\Common\Persistence\ObjectManager;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="homepage")
     */
    public function home(AgentRepository $agent, PropertyRepository $property, ObjectManager $manager)
    {
        $slider        = $manager->createQuery("SELECT p FROM App\Entity\Property p ORDER BY RAND()")->setMaxResults(3)->getResult();
        $latestAgent   = $manager->createQuery("SELECT a FROM App\Entity\Agent a ORDER BY a.id DESC")->setMaxResults(3)->getResult();
        $latestProps   = $manager->createQuery("SELECT p FROM app\Entity\Property p ORDER BY p.id DESC")->setMaxResults(4)->getResult();
        return $this->render('home.html.twig', [
            'slider' => $slider,
            'agents' => $latestAgent,
            'properties' => $latestProps,
        ]);
    }

    /**
     * @Route("/search", name="property_search")
     */
    public function handleSearch(Request $request, PropertyRepository $repo)
    {
        if ($request->isMethod('POST'))
        {
            $keyword    = $request->request->get('property_search')['keyword'];
            $status     = $request->request->get('property_search')['status'];
            $type       = $request->request->get('property_search')['type'];
            $bedrooms   = $request->request->get('property_search')['bedrooms'];
            $bathrooms  = $request->request->get('property_search')['bathrooms'];
            $garage     = $request->request->get('property_search')['garage'];
            $minPrice   = $request->request->get('property_search')['minPrice'];

            $property = $repo->propertySearch($keyword, $status, $type, $bathrooms, $bedrooms, $garage, $minPrice);

            return $this->render('search/search.html.twig', [
                'properties' => $property
            ]);

        } else {
            return $this->redirectToRoute('homepage');
        }

    }
}
