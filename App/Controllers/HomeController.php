<?php
    namespace Jotform\App\Controllers;

    class HomeController
    {
        public function index()
        {
           echo route('user',[':id1' => 5, ':id2' => 6]);
        }
    }