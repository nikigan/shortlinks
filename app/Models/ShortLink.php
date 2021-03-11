<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShortLink extends Model
{
    use HasFactory;

    protected $fillable = ['short_link', 'redirect_link', 'commercial', 'end_time'];

    public function link_visits()
    {
        return $this->hasMany(LinkVisit::class);
    }
}
