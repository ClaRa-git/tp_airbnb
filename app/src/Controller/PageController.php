<?php

namespace App\Controller;

use Symplefony\Controller;

class PageController extends Controller
{
    // Page mentions légales
    public function legalNotice(): void
    {
        echo 'Les mentions légales !';
    }
}