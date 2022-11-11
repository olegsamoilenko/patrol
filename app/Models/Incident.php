<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Incident extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'patrol',
        'address',
        'name',
        'document',
        'document_type',
        'document_type_other',
        'car_number',
        'car_type',
        'car_brand',
        'car_model',
        'car_color',
        'comment',
    ];

//    public function registerMediaCollections(): void
//    {
//        $this->addMediaCollection('file');
//        //add options
//        ...
//
//    // you can define as many collections as needed
//    $this->addMediaCollection('my-other-collection')
//        //add options
//        ...
//}
}
