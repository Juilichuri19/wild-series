<?php
// src/Controller/CategoryController.php
namespace App\Controller;

use App\Entity\Category;
use App\Entity\Program;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/category", name="category_")
 */
class CategoryController extends AbstractController
{
    /**
     * show all records from category entity
     *
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();

        return $this->render('category/index.html.twig', [
            'categories' => $categories,
        ]);
    }

    /**
     * @Route("/{categoryName}", methods={"GET"}, name="show")
     */
    public function show(string $categoryName): Response
    {
        $category = $this->getDoctrine()
           ->getRepository(Category::class)
           ->findOneBy(
               ['name' => $categoryName],
           );

        if (!$category) {
            throw $this->createNotFoundException(
                'No category with is : ' .$categoryName. ' found in category\'s table.'
            );
        } else {
            $program = $this->getDoctrine()
                ->getRepository(Program::class)
                ->findBy(
                    ['category' => $category],
                    ['id' => 'DESC'],
                    3
                );
        }

        return $this->render('category/show.html.twig', [
            'category' => $category,
            'programs' => $program,
        ]);
    }

}
