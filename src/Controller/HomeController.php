<?php

namespace App\Controller;

final readonly class HomeController
{

    public function index() : void
    {
        include "../src/view/welcome.php";
    }

}