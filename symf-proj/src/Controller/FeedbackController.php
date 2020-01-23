<?php

namespace App\Controller;

use App\Entity\Feedback;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\FeedbackType;

class FeedbackController extends AbstractController
{
    /**
     * @Route("/feedback", name="feedbackForm")
     * @param Request $request
     * @return Response
     */
    public function index(Request $request): Response
    {
        $form = $this->createForm(FeedbackType::class);
        $form->add('submit', SubmitType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /*** @var $feedback Feedback */
            $feedback = $form->getData();

            $em = $this->getDoctrine()->getManager();

            $em->persist($feedback);
            $em->flush();

            $this->addFlash('success', 'Thank you for your feedback!');

            return $this->redirectToRoute('feedbackForm');
        }

        return $this->render('@TwigTemplate/feedback/index.html.twig', [
            'feedback_form' => $form->createView()
        ]);
    }
}