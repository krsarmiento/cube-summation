<?php

namespace Acme\DemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class WelcomeController extends Controller
{
    private $result = array();
    private $dimension;
    private $queries;
    private $cube;

    public function indexAction(Request $request) {
        $result = array();
        $form = $this->createFormBuilder(array())
            ->add('dimension', 'integer', array('label' => 'Cube Dimension'))
            ->add('lines', 'integer', array('label' => '# of queries'))
            ->add('queries', 'textarea', array('label' => 'Queries'))
            ->getForm();

        if ($request->getMethod() == 'POST') {
            $form->bindRequest($request);
            $data = $form->getData();
            $this->dimension = $data['dimension'];
            $this->queries = $data['queries'];
            $result = $this->execute();
        }

        return $this->render('AcmeDemoBundle:Welcome:index.html.twig', array('form' => $form->createView(), 'result' => $result));
    }

    public function execute() {
        $this->cube = array();

        foreach (range(1, $this->dimension) as $x)  {
            foreach (range(1, $this->dimension) as $y)  {
                foreach (range(1, $this->dimension) as $z) {
                    $this->cube[$x][$y][$z] = 0;
                }
            }
        }

        return $this->parse();
    }

    public function parse(){
        $commands = explode("\n", $this->queries);
        foreach ($commands as $command){
            $params = explode(' ', $command);
            $args = array_slice($params, 1);
            if ($params[0] == 'QUERY') {
                $this->result[] = $this->makeQuery($args);
            }else if($params[0] == 'UPDATE') {
                $this->makeUpdate( $args );
            }
        }

        return $this->result;
    }

    public function makeQuery($args){
        $result = 0;
        $coord1 = array(
            'x' => $args[0],
            'y' => $args[1],
            'z' => $args[2]
        );
        $coord2 = array(
            'x' => $args[3],
            'y' => $args[4],
            'z' => $args[5]
        );

        foreach (range(1, $this->dimension) as $x)  {
            foreach (range(1, $this->dimension) as $y)  {
                foreach (range(1, $this->dimension) as $z) {
                    $xValid = $x >= $coord1['x'] && $x <= $coord2['x'];
                    $yValid = $y >= $coord1['y'] && $x <= $coord2['y'];
                    $zValid = $z >= $coord1['z'] && $x <= $coord2['z'];
                    if( $xValid && $yValid && $zValid) {
                        $result += $this->cube[$x][$y][$z];
                    }
                }
            }
        }

        return $result;
    }

    public function makeUpdate($args){
        $this->cube[ $args[0] ][ $args[1] ][ $args[2] ] = intval($args[3]);
    }
}