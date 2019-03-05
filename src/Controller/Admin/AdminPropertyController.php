<?php

namespace App\Controller\Admin;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\PropertyRepository;

class AdminPropertyController extends AbstractController 
{
    
    /**
     * @Route("/admin/property", name="admin_property")
     */
    public function index(PropertyRepository $repo)
    {
        return $this->render('admin/property/index.html.twig', [
            'properties' => $repo->findAll()
        ]);
    }

}