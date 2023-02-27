<?php

namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use App\Form\Repertoire1Type;
use App\Entity\Repertoire;
use App\Repository\DocumentRepository;
use App\Repository\RepertoireRepository;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;



class HomeController extends AbstractController
{
    /**
     * @Route("/", name="base", methods={"GET" , "POST"})

     */
    public function base( DocumentRepository $documentRepository , RepertoireRepository $repertoireRepository): Response

    { 

       
           $user = $this->get('security.token_storage')->getToken()->getUser();
        //    dd($user);
                return $this->render('base.html.twig' , [
                    'repertoires' => $repertoireRepository->findtop($user),
                    'files' => $documentRepository->findtop($user),
                    'documents' =>$user->getDocument(),
                   
                ]);
    }
 

}
