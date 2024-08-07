<?php

namespace App\Controller;

use App\Entity\Product;
use App\Form\CsvUploadType;
use App\Form\ProductType;
use App\Repository\ProductRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class ProductController extends AbstractController
{

    #[Route('/product/create', name: 'app_product_create')]
    public function index(Request $request, EntityManagerInterface $entityManager): Response
    {
        $product = new Product();
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {


            $product->setCreatedAt(new \DateTimeImmutable());

            $entityManager->persist($product);
            $entityManager->flush();

            $this->addFlash(
                'info',
                'Product created successfully!'
            );

            return $this->redirectToRoute('app_home');
        }

        return $this->render('product/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    #[Route('/product/{id}/edit', name: 'edit_product')]
    public function edit(Request $request, Product $product, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProductType::class, $product);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $product->setCreatedAt(new \DateTimeImmutable());

            $entityManager->flush();

            $this->addFlash(
                'info',
                'Product updated successfully!'
            );

            return $this->redirectToRoute('app_home');
        }

        return $this->render('product/edit.html.twig', [
            'form' => $form->createView(),
            'product' => $product,
        ]);
    }


    #[Route('/product/{id}/delete', name: 'delete_product', methods: ['POST'])]
    public function delete(Product $product, EntityManagerInterface $entityManager): Response
    {
        $entityManager->remove($product);
        $entityManager->flush();

        $this->addFlash(
            'info',
            'Product Deleted successfully!'
        );

        return $this->redirectToRoute('app_home');
    }


    #[Route('/product/import', name: 'import_product')]
    public function import(Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CsvUploadType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $csvFile = $form->get('csv_file')->getData();

            if ($csvFile) {
                $originalFilename = pathinfo($csvFile->getClientOriginalName(), PATHINFO_FILENAME);
                $newFilename = $originalFilename . '-' . uniqid() . '.' . $csvFile->guessExtension();

                try {
                    $csvFile->move(
                        $this->getParameter('kernel.project_dir') . '/public/uploads/',
                        $newFilename
                    );

                    $filePath = $this->getParameter('kernel.project_dir') . '/public/uploads/' . $newFilename;
                    $this->processCsv($filePath, $entityManager);

                    $this->addFlash(
                        'info',
                        'Product imported successfully!'
                    );

                    return $this->redirectToRoute('app_home');
                } catch (FileException $e) {
                    $this->addFlash('error', 'There was an error uploading the file.');
                }
            }
        }

        return $this->render('product/import.html.twig', [
            'form' => $form->createView(),
        ]);
    }



    private function processCsv(string $filePath, EntityManagerInterface $entityManager): void
    {
        if (($handle = fopen($filePath, 'r')) !== false) {

            fgetcsv($handle);

            while (($data = fgetcsv($handle, 1000, ',')) !== false) {

                $name = $data[0];
                $price = $data[1];
                $stock = $data[2];
                $description = $data[3];

                $product = new Product();
                $product->setName($name);
                $product->setPrice($price);
                $product->setStock($stock);
                $product->setDescription($description);
                $product->setCreatedAt(new \DateTimeImmutable());

                $entityManager->persist($product);
            }

            $entityManager->flush();

            fclose($handle);
        }
    }

    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }


    #[Route('/product/export', name: 'export_product')]
    public function export(): Response
    {

        $products = $this->entityManager->getRepository(Product::class)->findAll();

        $response = new Response();
        $response->headers->set('Content-Type', 'text/csv');
        $response->headers->set('Content-Disposition', 'attachment;filename="products.csv"');

        $output = fopen('php://output', 'w');

        fputcsv($output, ['ID', 'Name', 'Price', 'Stock', 'Description', 'Created At']);

        foreach ($products as $product) {
            fputcsv($output, [
                $product->getId(),
                $product->getName(),
                $product->getPrice(),
                $product->getStock(),
                $product->getDescription(),
                $product->getCreatedAt()->format('Y-m-d H:i:s'),
            ]);
        }

        fclose($output);

        return $response;
    }
}
