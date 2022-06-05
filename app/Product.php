<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\Collection;
use Cviebrock\EloquentSluggable\Sluggable;

class Product extends Model
{
    use Sluggable;

    protected $fillable = [
        'name', 'description','image', 'pro_details'
    ];

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }
    public function gallery()
    {
        return $this->hasMany(ProductGallery::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }

    public function attributes()
    {
        return $this->belongsToMany(Attribute::class)->using(ProductAttributeValues::class)->withPivot(['value_id']);
    }

    public function rate()
    {
        return $this->hasMany(ProductRate::class);
    }

    public function orders()
    {
        return $this->belongsToMany(Order::class);
    }

    public function prices()
    {
        return $this->hasMany(Price::class);
    }
    public function price()
    {
        return $this->hasOne(Price::class )->where('status' , 1);
    }

    public function inventory()
    {
        $prices =  $this->hasOne(Price::class );
        return $prices->sum('inventory');
    }

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }

}
