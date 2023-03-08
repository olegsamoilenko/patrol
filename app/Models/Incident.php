<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Incident extends Model implements HasMedia
{
    use HasFactory;
    use InteractsWithMedia;
    use SoftDeletes;

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
        'user_id',
        'deleted_by',
    ];

    public function scopeOnlyTheirOwn($query, $district)
    {
        return $query->where('user_id', auth()->user()->id);
    }

    public function scopeDistrict($query, $district)
    {
        return $query->where('district_id', $district);
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

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
