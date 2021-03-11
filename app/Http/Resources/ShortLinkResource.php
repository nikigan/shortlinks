<?php

namespace App\Http\Resources;

use App\Http\Controllers\AdsController;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Storage;

class ShortLinkResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'short_link' => $this->short_link,
            'redirect_link' => $this->redirect_link,
            'commercial' => $this->commercial,
            'statistic_link' => url('/stat/' . $this->statistic_link),
            'ad_url' => $this->when($this->commercial, function () {
                $files = Storage::disk('public')->allFiles('ads');
                return url('storage/' . $files[rand(0, count($files) - 1)]);
            })
        ];
    }
}
