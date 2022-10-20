<?php

namespace App\Http\Controllers;

use App\Http\Resources\ShortUrlResource;
use App\Models\ShortUrl;
use App\Services\Base62Service;
use App\Services\TokenService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;

class ShortUrlController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = ShortUrlResource::collection(ShortUrl::all());
        return Inertia::render("ShortUrl/Index", [
            "shortUrls" => $data
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(
        Request $request, 
        Base62Service $base62Service,
        TokenService $tokenService)
    {
        $long_url = $request->input('long_url');
        $user_id = auth()->user()->id;
        $token = $tokenService->nextToken();
        $base62_encoded = $base62Service->encode($token);

        ShortUrl::create([
            'long_url' => $long_url,
            'token' => $base62_encoded,
            'user_id' => $user_id
        ]);

        return Redirect::route('shorturl.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\ShortUrl  $shortUrl
     * @return \Illuminate\Http\Response
     */
    public function show(ShortUrl $shortUrl)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ShortUrl  $shortUrl
     * @return \Illuminate\Http\Response
     */
    public function edit(ShortUrl $shortUrl)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\ShortUrl  $shortUrl
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ShortUrl $shortUrl)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ShortUrl  $shortUrl
     * @return \Illuminate\Http\Response
     */
    public function destroy(ShortUrl $shortUrl)
    {
        //
    }

    public function redirect($token)
    {
        $shortUrl = ShortUrl::where('token', $token)->first();
        if (isset($shortUrl)) {
            return redirect($shortUrl->long_url);
        } else {
            return response([
                'error' => "Invalid token {$token}"
            ], 404);
        }
    }
}
