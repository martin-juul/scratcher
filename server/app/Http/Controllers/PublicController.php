<?php
declare(strict_types=1);

namespace App\Http\Controllers;

class PublicController extends Controller
{
    public function fallback()
    {
        return view('home');
    }
}
