<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\SearchProductType;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(PaginatorInterface $paginator, Request $request, EntityManagerInterface $entityManager): Response
    {

        $form = $this->createForm(SearchProductType::class);
        $form->handleRequest($request);

        $searchTerm = null;

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $searchTerm = $data['name'];
        }

        $queryBuilder = $entityManager->getRepository(Product::class)
            ->createQueryBuilder('p');

        if (!empty($searchTerm)) {
            $queryBuilder->where('p.name LIKE :name')
                ->setParameter('name', '%' . $searchTerm . '%');
        }

        $query = $queryBuilder->getQuery();

        $pagination = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            3,
            [
                'defaultSortFieldName' => 'p.id',
                'defaultSortDirection' => 'desc',
                'sortFieldParameterName' => 'sort',
                'sortDirectionParameterName' => 'direction'
            ]
        );

        return $this->render('home/index.html.twig', [
            'form' => $form->createView(),
            'pagination' => $pagination,
            'searchTerm' => $searchTerm,
        ]);
    }
}
