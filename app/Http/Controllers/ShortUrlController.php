<?php

namespace App\Http\Controllers;

use App\Http\Resources\ShortUrlResource;
use App\Models\ShortUrl;
use App\Models\ShortUrlView;
use App\Services\Base62Service;
use App\Services\ShortUrlViewService;
use App\Services\TokenService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;
use chillerlan\QRCode\QRCode;
use chillerlan\QRCode\QROptions;
use App\Services\PrometheusService;

class ShortUrlController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = ShortUrlResource::collection(ShortUrl::orderBy('created_at', 'desc')->get());
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

        $shortUrl = ShortUrl::create([
            'long_url' => $long_url,
            'token' => $base62_encoded,
            'user_id' => $user_id
        ]);

        ShortUrlView::create([
            'short_url_id' => $shortUrl->id,
            'count' => 0
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

    public function redirect($token, 
        ShortUrlViewService $shortUrlViewService,
        PrometheusService $prometheusService)
    {
        $shortUrl = ShortUrl::where('token', $token)->first();
        if (isset($shortUrl)) {
            $shortUrlViewService->increment($token, 1);
            
            # Prometheus
            $prometheusService->incrementViews($token, 1);

            return redirect($shortUrl->long_url);
        } else {
            return response([
                'error' => "Invalid token {$token}"
            ], 404);
        }
    }

    public function getQRCode(Request $request)
    {
        $token = $request->input('token');
        $longUrl = ShortUrl::where('token', $token)->first()->long_url;
        $full_path = public_path() . '/' . "{$token}.svg";
        $options = new QROptions([
            'version'    => 5,
            'outputType' => QRCode::OUTPUT_MARKUP_SVG,
            'eccLevel'   => QRCode::ECC_L,
        ]);
        $generator = new QRCode($options);
        $generator->render($longUrl, $full_path);
        return Redirect::route('shorturl.index');
    }
}
