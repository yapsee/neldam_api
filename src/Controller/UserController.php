<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    
    public function index()
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
           
        ]);
    }
}