<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ExceptionController extends AbstractController
  {
      /**
       * @Route("/error", name="error")
       */
      public function showException(Request $request): Response
      {
          $exception = $request->attributes->get('exception');
          if ($exception instanceof NotFoundHttpException) {
              return $this->render('404.html.twig', [], Response::HTTP_NOT_FOUND);
          }

          // Gérez d'autres types d'exceptions ou d'erreurs ici si nécessaire

          // Par défaut, renvoyez une page d'erreur générique
          return $this->render('404.html.twig');    }
  }
