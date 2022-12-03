<?php

namespace App\Models;

use App\Models\Listing;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Listing extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'company',
        'location',
        'website',
        'email',
        'tags',
        'description'
    ];
    public function scopeFilter($query, array $filters){
        //if this is not false then move on
        if($filters['tag'] ?? false){
            // sql like query
            $query->where('tags', 'like', '%' . request('tag') . '%');

        }

        if($filters['search'] ?? false){
            $query->where('tags', 'like', '%' . request('search') . '%')
            ->orWhere('description', 'like', '%' . request('search') . '%')
            ->orWhere('title', 'like', '%' . request('search') . '%')
            ;
        }
    }

    //Relationship to User
    public function user(){
        return $this->belongsTo(Listing::class, 'user_id');
    }
}