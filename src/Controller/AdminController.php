<?php

namespace App\Controller;

use App\Entity\Repertoire;
use App\Entity\User;
use App\Repository\DocumentRepository;
use App\Repository\RepertoireRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin", name="app_admin")
     */
    public function index( DocumentRepository $documentRepository , RepertoireRepository $repertoireRepository , UserRepository $userepo): Response
    {           $user = $this->get('security.token_storage')->getToken()->getUser();

        return $this->render('admin/index.html.twig', [
            'users' =>$userepo->findAll(),
            'repertoires' => $repertoireRepository->findtop($user),
            'files' => $documentRepository->findtop($user),
            'documents' =>$user->getDocument(),
        ]);
    }
    /**
     * @Route("/profile/{id}", name="user_show")
     */
    public function prof( DocumentRepository $documentRepository , RepertoireRepository $repertoireRepository): Response
    {           $user = $this->get('security.token_storage')->getToken()->getUser();
        return $this->render('profile.html.twig', [
            'repertoires' => $repertoireRepository->findtop($user),
            'files' => $documentRepository->findtop($user),
            'documents' =>$user->getDocument(),
            'user' => $user        ]);
    }
    /**
     * @Route("/editprofile/{id}", name="edit_us")
     */
    public function pp( User $user , Request $request , UserRepository $userepo): Response
    {     
        $user->setFirstname($request->request->get('fname'));
        $user->setLastname($request->request->get('lname'));
        $user->setEmail($request->request->get('uname'));
        $userepo->add($user , true);
        return $this->redirectToRoute('base', [], Response::HTTP_SEE_OTHER);

    }

   
    /**
     * @Route("/verify/{id}", name="verify_user")
     * @IsGranted("ROLE_ADMIN")
     */
    public function verf( User $user, UserRepository $userepo): Response
    {     
        $user->setIsVerified(true);
        $userepo->add($user , true);
                return $this->redirectToRoute('app_admin', [], Response::HTTP_SEE_OTHER);

    }
    /**
     * @Route("/remove/{id}", name="remove_user" , methods={"GET" , "POST"})
     * @IsGranted("ROLE_ADMIN")
     */
    public function delete( User $user, UserRepository $userepo ): Response
    {     
        $userepo->remove($user , true);
    
        return $this->redirectToRoute('app_admin', [], Response::HTTP_SEE_OTHER);

    }
   
}
