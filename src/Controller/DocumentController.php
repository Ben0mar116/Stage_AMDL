<?php

namespace App\Controller;
use \Datetime;
use App\Entity\Document;
use App\Entity\Repertoire;
use App\Form\DocumentType;
use App\Repository\DocumentRepository;
use App\Repository\RepertoireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;


/**
 * @Route("/document")
 */
class DocumentController extends AbstractController
{
    /**
     * @Route("/", name="app_document_index", methods={"GET"})
     */
    public function index(DocumentRepository $documentRepository): Response
    {       
        $user = $this->get('security.token_storage')->getToken()->getUser();
        return $this->render('document/index.html.twig', [
            'documents' =>$user->getDocument(),
            'repertoires' => $user->getRepertoire(),
        ]);
    }

    /**
     * @Route("/newdoc", name="sub_document", methods={"GET", "POST"})
     */
    public function subfile(RepertoireRepository $rpertoire,Request $request, DocumentRepository $documentRepository): Response
    {$user = $this->get('security.token_storage')->getToken()->getUser();
        $document = new Document();
        $currentDate = new DateTime();

        // $form = $this->createForm(DocumentType::class, $document);
        // $form->handleRequest($request);
            
        $parent = $rpertoire->find($request->request->get('parent'));
            $document->setUser($user); // set user
            $uploads_directory = $this->getParameter('targetDirectory');
            $target = $uploads_directory . basename($_FILES['Upfile']['name']);
            $document->setType($_FILES["Upfile"]["type"]);
            $document->setChemin(basename($_FILES["Upfile"]["name"]));
            $document->setIntitule($request->request->get('nnfile' , ''));
            $document->setTaille($_FILES["Upfile"]["size"]);
            $document->setDateCreation($currentDate);
            $document->setRepertoire($parent);

            try {

                move_uploaded_file($_FILES["Upfile"]["tmp_name"] , $target );
                        }catch(FileException $e){               

            }
            $documentRepository->add($document, true);
            return $this->redirectToRoute('base', [], Response::HTTP_SEE_OTHER);

       
    }
    /**
     * @Route("/new", name="app_document_new", methods={"GET", "POST"})
     */
    public function new(Request $request, DocumentRepository $documentRepository): Response
    {$user = $this->get('security.token_storage')->getToken()->getUser();
        $document = new Document();
        $currentDate = new DateTime();

  

       
        // $form = $this->createForm(DocumentType::class, $document);
        // $form->handleRequest($request);
            
      
        $document->setUser($user); // set user
        //  $fish =$form['chemin']->getData();
        $uploads_directory = $this->getParameter('targetDirectory');
        $target = $uploads_directory . basename($_FILES['Upfile']['name']);
        $document->setType($_FILES["Upfile"]["type"]);
        $document->setChemin(basename($_FILES["Upfile"]["name"]));
        $document->setIntitule($request->request->get('fileN' , ''));
        $document->setTaille($_FILES["Upfile"]["size"]);
        $document->setDateCreation($currentDate);

        try {

            move_uploaded_file($_FILES["Upfile"]["tmp_name"] , $target );
                    }catch(FileException $e){               

        }
        $documentRepository->add($document, true);
        return $this->redirectToRoute('base', [], Response::HTTP_SEE_OTHER);

       
    }

    /**
     * @Route("/{id}", name="app_document_show", methods={"GET"})
     */
    public function show(Document $document): Response
    {
        return $this->render('document/show.html.twig', [
            'document' => $document,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_document_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Document $document, DocumentRepository $documentRepository , RepertoireRepository $repertoireRepository): Response
    {
        // $form = $this->createForm(DocumentType::class, $document);
        // $form->handleRequest($request);
    
       $uploads_directory = $this->getParameter('targetDirectory');
       if($_FILES['load']['name']){
              $oldlink = $uploads_directory . $document->getChemin();
              unlink($oldlink);
        $document->setType($_FILES["load"]["type"]);
        $document->setChemin(basename($_FILES["load"]["name"]));
        $document->setTaille($_FILES["load"]["size"]);
        
        $target = $uploads_directory . basename($_FILES['load']['name']);
        try {

            move_uploaded_file($_FILES["load"]["tmp_name"] , $target );
                    }catch(FileException $e){               

        }
    }
    $parent = $repertoireRepository->findOneBySomeField($request->request->get('alter_parent'));
    $document->setRepertoire($parent);
    $document->setIntitule($request->request->get('altered_name'));
            $documentRepository->add($document, true);

        

            return $this->redirectToRoute('base', [], Response::HTTP_SEE_OTHER);

    }

    

    /**
     * @Route("/{id}", name="app_document_delete", methods={"POST"})
     */
    public function delete(Request $request, Document $document, DocumentRepository $documentRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$document->getId(), $request->request->get('_token'))) {
            $uploads_directory = $this->getParameter('targetDirectory');
            unlink($uploads_directory .'/' . $document->getChemin() );
            $documentRepository->remove($document, true);

        }

        return $this->redirectToRoute('base', [], Response::HTTP_SEE_OTHER);
    }
}
