<?php

namespace Acme\DemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class WelcomeController extends Controller
{
    public function indexAction(Request $request)
    {
        $defaultData = array();
        $form = $this->createFormBuilder($defaultData)
            ->add('attempts', 'text', array('label' => 'Number of Attempts'))
            ->getForm();

        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);
            $data = $form->getData();
            return $this->forward('AcmeDemoBundle:Welcome:evaluate', array(
                'times'  => $data['attempts']
            ));
        }

        return $this->render('AcmeDemoBundle:Welcome:index.html.twig', array('form' => $form->createView()));
    }

    public function evaluateAction($times) {
        return $this->render('AcmeDemoBundle:Welcome:evaluate.html.twig', array('times' => intval($times)));        
    }
}
