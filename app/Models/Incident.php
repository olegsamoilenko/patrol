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
        'district_id',
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

    public function scopePatrol($query, $patrol)
    {
        return $query->where('patrol', $patrol);
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'LIKE', "%{$search}%")
            ->orWhere('car_number', 'LIKE', "%{$search}%")
        ;
    }

    public function getCount()
    {
        return $this->count();
    }

    public function getTodayIncidentCount()
    {
        return $this->where('updated_at', '>', now()->today())->count();
    }

    public function getLastWeekIncidentCount()
    {
        return $this->where('updated_at', '>', now()->subWeek())->count();
    }

    public function getLastMonthIncidentCount()
    {
        return $this->where('updated_at', '>', now()->subMonth())->count();
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }
}
