<?php

namespace App\Controller;

use App\Entity\Advert;
use App\Form\AdvertEditType;
use App\Form\AdvertType;
use App\Repository\AdvertRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

/**
 * @Route("/advert")
 */
class AdvertController extends AbstractController
{

  /**
   * @Route("/view/{id}", name="view", requirements={"id" = "\d+"})
   * @return Response
   */
  public function view(Advert $advert)
  {
    if (null === $advert) {
      throw new NotFoundHttpException("The advert n° ".$id." doesn't exist.");
    }
    return $this->render('Advert/view.html.twig', array(
      'advert' => $advert
    ));
  }

  /**
   * @Route("/add", name="add")
   * @param Request $request
   * @return Response
   */
  public function add(Request $request): Response
  {
    $advert = new Advert();
    $form   = $this->get('form.factory')->create(AdvertType::class, $advert);

    if ($request->isMethod('POST') && $form->handleRequest($request)->isValid()) {
      $em = $this->getDoctrine()->getManager();
      $file = $request->files->get('advert')['image'];
      if ($file) {
        $filename = md5(uniqid()) . '.' . $file->guessClientExtension();
        $file->move(
          $this->getParameter('uploads_directory'),
          $filename
        );

        $advert->setImage($filename);
      }
      $em->persist($advert);
      $em->flush();

      $request->getSession()->getFlashBag()->add('new', 'Advert registered successfully.');

      return $this->redirectToRoute('view', array('id' => $advert->getId()));
    }

    return $this->render('Advert/add.html.twig', array(
      'form' => $form->createView(),
    ));
  }

  /**
   * @Route("/edit/{id}", name="edit", requirements={"id" = "\d+"})
   */
  public function edit(Advert $advert, Request $request, EntityManagerInterface $em)
  {
    $form = $this->createForm(AdvertType::class);

    $form->handleRequest($request);

    if (null === $advert) {
      throw new NotFoundHttpException("The advert n° ".$id." doesn't exist.");
    }

    if ($form->isSubmitted() && $form->isValid()) {

        $advert = $form->getData();
        $em->persist($advert);
        $em->flush();

      $this->addFlash('modify', 'The advert was modified successfully.');

      return $this->redirectToRoute('view', array('id' => $advert->getId()));
    }

    return $this->render('Advert/edit.html.twig', array(
      'advert' => $advert,
      'form'   => $form->createView(),
    ));
  }

  /**
   * @Route("/delete/{id}", name="delete", requirements={"id" = "\d+"})
   */
  public function delete(Advert $advert)
  {
    $em = $this->getDoctrine()->getManager();

    if (null === $advert) {
      throw new NotFoundHttpException("The advert n° ".$id." doesn't exist.");
    }

    $em->remove($advert);
    $em->flush();

    $this->addFlash('deleted', 'The advert was deleted successfully!');

    return $this->redirectToRoute('home');
  }
}
