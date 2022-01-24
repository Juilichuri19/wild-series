<?php
// src/Controller/ProgramController.php
namespace App\Controller;

use App\Entity\Category;
use App\Entity\Program;
use App\Entity\Episode;
use App\Entity\Season;
use App\Form\CategoryType;
use App\Form\EpisodeType;
use App\Service\Slugify;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use App\Form\ProgramType;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/program", name="program_")
 */
class ProgramController extends AbstractController
{
    /**
     * show all records from program entity
     *
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findAll();

        return $this->render('program/index.html.twig', [
            'programs' => $programs,
        ]);
    }

    /**
     * The controller for the program add form
     * Display the form or deal with it
     *
     * @Route("/new", name="new")
     */
    public function new(Request $request, Slugify $slugify): Response
    {

        $program = new Program();
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $slug = $slugify->generate($program->getTitle());
            $program->setSlug($slug);
            $entityManager->persist($program);
            $entityManager->flush();

            return $this->redirectToRoute('program_index');
        }
        // Render the form
        return $this->render('program/new.html.twig',
            ["form" => $form->createView()]);
    }

    /**
     * @Route("/{slug}", methods={"GET"}, name="show")
     */
    public function show(Program $program): Response
    {
        return $this->render('program/show.html.twig', [
            'program' => $program,
        ]);
    }

    /**
    * @Route("/{id}/edit", name= "edit", methods= {"GET", "POST"})
    */

    public function edit(Request $request, Program $program, EntityManagerInterface $entityManager, Slugify $slugify): Response
    {
        $form = $this->createForm(ProgramType::class, $program);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $slug = $slugify->generate($program->getTitle());
            $program->setSlug($slug);
            $entityManager->persist($program);
            $entityManager->flush();

            return $this->redirectToRoute('program_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->renderForm('program/edit.html.twig', [
            'program' => $program,
            'form' => $form,
        ]);
    }


    /**
     * @Route("/{program<^[0-9]+$>}/seasons/{season<^[0-9]+$>}", methods={"GET"}, name="season_show")
     */

    public function showSeason(Program $program, Season $season): Response
    {
        return $this->render('program/season_show.html.twig', [
            'program' => $program,
            'season' => $season,
        ]);
    }

    /**
     * @Route("/{program_id<^[0-9]+$>}/season/{season_id<^[0-9]+$>}/episode/{episode_id<^[0-9]+$>}", name="episode_show", methods={"GET"})
     * @ParamConverter("program", class="App\Entity\Program", options={"mapping": {"program_id": "id"}})
     * @ParamConverter("season", class="App\Entity\Season", options={"mapping": {"season_id": "id"}})
     * @ParamConverter("episode", class="App\Entity\Episode", options={"mapping": {"episode_id": "id"}})
     */
    public function showEpisode(Program $program, Season $season, Episode $episode): Response
    {
        return $this->render('program/episode_show.html.twig', [
            'program' => $program,
            'season' => $season,
            'episode' => $episode,
        ]);
    }


}
