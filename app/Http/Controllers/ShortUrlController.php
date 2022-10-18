<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class ShortUrlController extends Controller
{
    public function index() {
        return Inertia::render("ShortUrl/Index", [
            "message" => "Hello Voters! Welcome to Laravel Inertia world!"
        ]);
    }
}
