<?php

namespace App\Controller;

use App\Entity\Album;
use App\Entity\Artiste;
use App\Entity\Track;
use App\Form\ArtisteType;
use App\Repository\AlbumRepository;
use App\Repository\ArtisteRepository;
use App\Repository\TrackRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/artiste")
 */
class ArtisteController extends AbstractController
{
    /**
     * @Route("/", name="artiste_index", methods={"GET"})
     * @param ArtisteRepository $artisteRepository
     * @return Response
     */
    public function index(ArtisteRepository $artisteRepository): Response
    {
        return $this->render('artiste/index.html.twig', [
            'artistes' => $artisteRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="artiste_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $artiste = new Artiste();
        $form = $this->createForm(ArtisteType::class, $artiste);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($artiste);
            $entityManager->flush();

            return $this->redirectToRoute('artiste_index');
        }

        return $this->render('artiste/new.html.twig', [
            'artiste' => $artiste,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/json", name="json", methods={"GET","POST"})
     * @param ArtisteRepository $artisteRepository
     * @return JsonResponse
     */
    public function jsonArtiste(ArtisteRepository $artisteRepository)
    {
        $rawData = $artisteRepository->findAll();
        $data = array_map(function (Artiste $item) {
            return [
                'id' => $item->getId(),
                'name' => $item->getName()
            ];
        }, $rawData);
        $response = new JsonResponse(['data' => $data]);
        return $response;
    }

    /**
     * @Route("/{id}", name="artiste_show", methods={"GET"})
     * @param Artiste $artiste
     * @param AlbumRepository $albumRepository
     * @param TrackRepository $trackRepository
     * @return Response
     */
    public function show(Artiste $artiste, AlbumRepository $albumRepository, TrackRepository $trackRepository): Response
    {
        return $this->render('artiste/show.html.twig', [
            'artiste' => $artiste,
            'albums' => $albumRepository->albumsByArtist($artiste),
            'tracks' => $trackRepository->tracksByArtist($artiste)
        ]);
    }

    /**
     * @Route("/{id}/edit", name="artiste_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Artiste $artiste
     * @return Response
     */
    public function edit(Request $request, Artiste $artiste): Response
    {
        $form = $this->createForm(ArtisteType::class, $artiste);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->getDoctrine()->getManager()->flush();


            return $this->redirectToRoute('artiste_index');
        }

        return $this->render('artiste/edit.html.twig', [
            'artiste' => $artiste,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="artiste_delete", methods={"DELETE"})
     * @param Request $request
     * @param Artiste $artiste
     * @return Response
     */
    public function delete(Request $request, Artiste $artiste): Response
    {
        if ($this->isCsrfTokenValid('delete'.$artiste->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($artiste);
            $entityManager->flush();
        }

        return $this->redirectToRoute('artiste_index');
    }

    /**
     * @Route("/{id}/{album}", name="artiste_album", methods={"GET"})
     * @param Album $album
     * @param Artiste $artiste
     * @param AlbumRepository $albumRepository
     * @param TrackRepository $trackRepository
     * @return Response
     */
    public function album(Album $album, Artiste $artiste, AlbumRepository $albumRepository, TrackRepository $trackRepository): Response
    {
        return $this->render('album/show.html.twig', [
            'artiste' => $artiste,
            'album' => $albumRepository->albumArtist($album, $artiste)[0],
            'tracks' => $trackRepository->tracksByAlbum($album)
        ]);
    }

    /**
     * @Route("/{id}/0/{track}", name="artiste_track", methods={"GET"})
     * @param Artiste $artiste
     * @param Track $track
     * @return Response
     */
    public function track(Artiste $artiste, Track $track): Response
    {
        return $this->render('track/show.html.twig', [
            'artiste' => $artiste,
            'track' => $track,
            'album' => null
        ]);
    }

    /**
     * @Route("/{id}/{album}/{track}", name="artiste_album_track", methods={"GET"})
     * @param Artiste $artiste
     * @param Track $track
     * @param AlbumRepository $albumRepository
     * @return Response
     */
    public function albumTrack(Artiste $artiste, Track $track, AlbumRepository $albumRepository): Response
    {
        return $this->render('track/show.html.twig', [
            'artiste' => $artiste,
            'album' => $track->getAlbum() ? $albumRepository->find($track->getAlbum()) : null,
            'track' => $track
        ]);
    }


}
