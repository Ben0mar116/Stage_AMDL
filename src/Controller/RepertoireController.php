<?php

namespace App\Controller;
use \DateTime;
use App\Entity\Repertoire;
use App\Form\Repertoire1Type;
use App\Repository\RepertoireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/repertoire")
 */
class RepertoireController extends AbstractController
{
    /**
     * @Route("/", name="app_repertoire_index", methods={"GET" , "POST"})
     */
    public function index(Request $request , RepertoireRepository $repertoireRepository): Response
    {        $user = $this->get('security.token_storage')->getToken()->getUser();
            
        $repertoire = new Repertoire();
     
        return $this->renderForm('repertoire/index.html.twig', [
            'repertoires' => $user->getRepertoire(),
            'documents' =>$user->getDocument(),

      
                ]);
    }

    /**
     * @Route("/new", name="app_repertoire_new", methods={"GET", "POST"})
     */
    public function new(Request $request, RepertoireRepository $repertoireRepository): Response
    {   
         $user = $this->get('security.token_storage')->getToken()->getUser();
        $parent = $repertoireRepository->find($request->request->get('parent'));
        $currentdate = new DateTime();
        $repertoire = new Repertoire();
            // dd($request->request->get('parent'));
        $repertoire->setUntitule($request->request->get('fname'));
        $repertoire->setDateCreation($currentdate);
        $repertoire->setUser($user);
        $repertoire->setRepertoireParent($parent);
        $repertoireRepository->add($repertoire, true);
        return $this->redirectToRoute('base', [], Response::HTTP_SEE_OTHER);


     
    }

    /**
     * @Route("/{id}", name="app_repertoire_show", methods={"GET"})
     */
    public function show(Request $request, Repertoire $repertoire): Response
    {$user = $this->get('security.token_storage')->getToken()->getUser();
        $form = $this->createForm(Repertoire1Type::class, $repertoire);
        $form->handleRequest($request);
        return $this->render('repertoire/show.html.twig', [
            'repertoires' => $user->getRepertoire(),
            'folder' => $repertoire,
            'documents' =>$user->getDocument(),

       
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_repertoire_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Repertoire $repertoire, RepertoireRepository $repertoireRepository): Response
    {  
        $parent = $repertoireRepository->findOneBySomeField($request->request->get('altparent'));
        $repertoire->setUntitule($request->request->get('foldname'));
        $repertoire->setRepertoireParent($parent);
        $repertoireRepository->add($repertoire,true);
      
        return $this->redirectToRoute('app_repertoire_index', [], Response::HTTP_SEE_OTHER);

    }

    /**
     * @Route("/{id}", name="app_repertoire_delete", methods={"POST"})
     */
    public function delete(Request $request, Repertoire $repertoire, RepertoireRepository $repertoireRepository): Response
    {         $user = $this->get('security.token_storage')->getToken()->getUser();

        if ($this->isCsrfTokenValid('delete'.$repertoire->getId(), $request->request->get('_token'))) {
            $repertoireRepository->remove($repertoire, true);
        }

        return $this->redirectToRoute('app_repertoire_index', [], Response::HTTP_SEE_OTHER);
    }
}
