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
use DOMDocument;
use Exception;
use GuzzleHttp\Client;

class ShortUrlController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $auth_user = auth()->user();
        $user_id = empty($auth_user) ? null : $auth_user->id;
        $shortUrls = ShortUrl::where('user_id', $user_id)->orderBy('created_at', 'desc')->paginate(10);
        
        $data = ShortUrlResource::collection($shortUrls);

        $totalPages = $shortUrls->lastPage();
        $currentPage = $shortUrls->currentPage();
        $start = 1;
        $end = 1;
        if ($totalPages <= 5) {
            $end = $totalPages;
        } else {
            $start = $currentPage - 2;
            $end = $currentPage + 2;

            if ($start <= 0) {
                $end = $end + ($start + 1);
                $start = 1;
            } else if ($end > $totalPages) {
                $start = $start - ($totalPages - $end);
                $end = $totalPages;
            }
        }

        $links = [];
        for ($i = $start; $i <= $end; $i = $i + 1) {
            $links[] = [
                "index" => $i,
                "url" => $shortUrls->url($i)
            ];
        }

        return Inertia::render("ShortUrl/Index", [
            "shortUrls" => $data,
            "pagination" => [
                "totalPages" => $totalPages,
                "currentPage" => $shortUrls->currentPage(),
                "firstPageUrl" => $currentPage == 1 ? "" : $shortUrls->url(1),
                "lastPageUrl" => $currentPage == $totalPages ? "" : $shortUrls->url($totalPages),
                "nextPageUrl" => $currentPage == $totalPages ? "" : $shortUrls->nextPageUrl(),
                "previousPageUrl" => $currentPage == 1 ? "" : $shortUrls->previousPageUrl(),
                "links" => $links
            ]
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

        Log::debug("Token: {$token}");
        $base62_encoded = $base62Service->encode($token);

        $title = $long_url;

        try {
            $client = new Client();
            $response = $client->request("GET", $long_url);
            $html = (string) $response->getBody();
            $pattern = "/<title>(.+?)<\/title>/i";
            preg_match($pattern, $html, $matches);
            if (sizeof($matches) > 1) {
                $title = $matches[1];
            }
        } catch (Exception $ex) {
            Log::error($ex->getMessage());
        }

        Log::debug("URL title: {$title}");

        $shortUrl = ShortUrl::create([
            'long_url' => $long_url,
            'token' => $base62_encoded,
            'user_id' => $user_id,
            'title' => $title
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
