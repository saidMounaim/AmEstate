<?php

namespace App\Controller;

use App\Repository\AgentRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Agent;


class AgentController extends AbstractController
{

    /**
     * @Route("/agents", name="agent_list")
     */
    public function index(Request $request, AgentRepository $repo, PaginatorInterface $paginator)
    {
        $query = $repo->getAll();
        $pagination = $paginator->paginate(
            $query, /* query NOT result */
            $request->query->getInt('page', 1)/*page number*/,
            6/*limit per page*/
        );

        return $this->render('agent/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * @Route("/agent/{slug}", name="agent_show")
     */
    public function show(Agent $agent)
    {
        return $this->render('agent/show.html.twig', [
            'agent' => $agent
        ]);
    }

}