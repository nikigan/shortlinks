<?php

namespace App\Http\Controllers;

use App\Http\Requests\ShortLinkRequest;
use App\Http\Resources\ShortLinkResource;
use App\Models\LinkVisit;
use App\Models\ShortLink;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

class LinkController extends Controller
{

    public function redirect(Request $request, string $request_link)
    {
        $link = ShortLink::query()->where('short_link', $request_link)->firstOrFail();

        $link_visit = new LinkVisit;
        $link_visit->client_ip = $request->ip();
        $link_visit->short_link_id = $link->id;
        $link_visit->save();

        return new ShortLinkResource($link);
    }

    public function store(ShortLinkRequest $request)
    {
        $validated = $request->validated();

        $link_text = $validated['custom_shortlink'];

        if (!$link_text) {
            do {
                $link_text = Str::random(10);
                $existing_link = !!ShortLink::query()->where('short_link', $link_text)->count();
            } while ($existing_link);
        }

        if ($validated) {
            $link = new ShortLink;
            $link->fill([
                'short_link' => $link_text,
                'redirect_link' => $validated['redirect_url'],
                'commercial' => $validated['commercial'] ?? false,
                'end_time' => Carbon::parse($validated['end_date']) ?? now()->addDay()
            ]);

            do {
                $stat_link_text = Str::random(20);
                $existing_link = !!ShortLink::query()->where('statistic_link', $stat_link_text)->count();
            } while ($existing_link);

            $link->statistic_link = $stat_link_text;

            $link->save();

            return new ShortLinkResource($link);
        }

        abort(500, "Unknown error");
        return response();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
