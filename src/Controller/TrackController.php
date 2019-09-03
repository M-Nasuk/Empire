<?php

namespace App\Controller;

use App\Entity\Track;
use App\Form\TrackType;
use App\Repository\AlbumRepository;
use App\Repository\ArtisteRepository;
use App\Repository\TrackRepository;
use App\Service\FileUploader;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/track")
 */
class TrackController extends AbstractController
{
    /**
     * @Route("/", name="track_index", methods={"GET"})
     */
    public function index(TrackRepository $trackRepository): Response
    {
        return $this->render('track/index.html.twig', [
            'tracks' => $trackRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="track_new", methods={"GET","POST"})
     */
    public function new(Request $request, FileUploader $fileUploader, ArtisteRepository $artisteRepository): Response
    {
        $track = new Track();
        $form = $this->createForm(TrackType::class, $track);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            var_dump($form['file']->getData());
            if (isset($form['file']) && $form['file']->getData() !== null) {
                $file = $form['file']->getData();
                $name = $form['name']->getData();
                $newFile = $fileUploader->upload($file, $name);
                $track->setFile($newFile);
            }

            if (isset($form['url']) && $form['url']->getData() !== null) {
                $url = $form['url']->getData();
                $url;
            }
            
            $entityManager->persist($track);
            $entityManager->flush();

            return $this->redirectToRoute('track_index');
        }

        return $this->render('track/new.html.twig', [
            'track' => $track,
            'form' => $form->createView(),
            'artiste' => $artisteRepository->findAll()
        ]);
    }

    /**
     * @Route("/{id}", name="track_show", methods={"GET"})
     * @Security("is_granted('ROLE_CUSTOMER')")
     */
    public function show(Track $track, ArtisteRepository $artisteRepository, AlbumRepository $albumRepository): Response
    {

        return $this->render('track/show.html.twig', [
            'track' => $track,
            'artiste' => $artisteRepository->find($track->getArtiste()),
            'album' => $track->getAlbum() ? $albumRepository->find($track->getAlbum()) : null
        ]);
    }

    /**
     * @Route("/{id}/edit", name="track_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Track $track, FileUploader $fileUploader): Response
    {
        $form = $this->createForm(TrackType::class, $track);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if (isset($form['file'])) {
                $file = $form['file']->getData();
                $name = $form['name']->getData();
                $newFile = $fileUploader->upload($file, $name);
                $track->setFile($newFile);
            }
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('track_index');
        }

        return $this->render('track/edit.html.twig', [
            'track' => $track,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="track_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Track $track): Response
    {
        if ($this->isCsrfTokenValid('delete'.$track->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($track);
            $entityManager->flush();
        }

        return $this->redirectToRoute('track_index');
    }


}
