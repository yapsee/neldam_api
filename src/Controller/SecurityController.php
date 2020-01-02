<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Roles;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/api", name="")
 */
class SecurityController extends AbstractController
{
    /**
     * @Route("/admin", name="add_admin", methods={"POST"})
     */
    #cette commande permet a l'adminsys de creer un admin
    public function adminAdd(Request $request, SerializerInterface  $serializer,Roles $roles)
   {
        $data = $request->getContent();
        $user = $serializer->deserialize($data, User::class, 'json');
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $role = [];
        if ($roles->getLibelle() == "ADMIN") {
            if ($this->getUser()->getRoles()!= 'ROLE_ADMIN_SYS') {
                return $this->json([
                    'message' => 'vous devez etre un admin du systeme pour creer un admin'
                ]);
            }
            $role = (["ROLE_ADMIN"]);
        }
        
        $entityManager->flush();
        return  new JsonResponse(null, Response::HTTP_CREATED);
    }
}