<?php

namespace Acme\PostitBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

use Acme\PostitBundle\Entity\Postit;

class PostitController extends Controller
{
    public function indexAction()
    {
        return $this->render('PostitBundle::index.html.twig');
    }

    public function listAction()
    {
    	$repository = $this->getDoctrine()->getRepository('PostitBundle:Postit');
    	$postits = $repository->findAll();

    	$data = array();

    	foreach ($postits as $postit) {
    		$data[] = [
    			'id' 		=> $postit->getId(),
    			'date' 		=> $postit->getDate()->format("Y-m-d H:i:s"),
    			'content' 	=> $postit->getContent(),
    			'color' 	=> $postit->getColor()
    			];
    	}


    	//$data = [['id' => 1, 'date' => '2/6/15 12:37', 'content' => 'bosser', 'color' => 'blue']];

        $response = new JsonResponse();
        $response->setCharset('UTF-8');
        $response->setStatusCode(200);
/*
        $responseData = array(
            'error' => false,
            'data' => $data,
        );*/        
        $response->setData($data);

        return $response;
    }

    public function deleteAction(Request $request)
    {
    	$repository = $this->getDoctrine()->getRepository('PostitBundle:Postit');
    	$postit = $repository->findOneById($request->request->get('id'));

    	if (!$postit){
    		throw $this->createNotFoundException('Ce post-it n\'existe pas!');
    	}

    	$manager = $this->getDoctrine()->getManager();
    	$manager->remove($postit);
    	$manager->flush();
    	
        $this->get('session')->getFlashBag()->add('success', 'Le Post-it a bien été supprimé.');    

        return new JsonResponse();
        //return	$this->redirect($this->generateUrl('postit_index'));
    }

    public function addAction(Request $request)
    {
    	$newPostit = new Postit();
    	$newPostit->setDate(new \DateTime());
    	$newPostit->setContent($request->request->get('content'));

    	switch(rand(0,4)) {
    		case 0:
    			$color = 'blue';
    			break;
    		case 1:
    			$color = 'green';
    			break;
    		case 2:
    			$color = 'orange';
    			break;
    		case 3:
    			$color = 'purple';
    			break;
    		case 4:
    			$color = 'red';
    			break;    			    			    			
    	}
    	
    	$newPostit->setColor($color);

    	$manager = $this->getDoctrine()->getManager();
    	$manager->persist($newPostit);
    	$manager->flush();

    	return new JsonResponse();
    }
}

