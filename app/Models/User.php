<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use SoftDeletes;

    use HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'surname',
        'phone',
        'email',
        'password',
        'fcm_token',
        'is_activated',
        'is_headquarters',
        'formation_id',
        'battalion',
        'company',
        'platoon',
        'activated_by',
        'deleted_by',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'activated_by' => 'array',
        'deleted_by' => 'array',
    ];


    public function scopeRole($query, $role)
    {
        $query->whereHas('roles', function ($query) use ($role) {
            return $query->where('id', $role);
        });
    }

    public function scopeSearch($query, $search)
    {
        return $query->where('name', 'LIKE', "%{$search}%")
            ->orWhere('surname', 'LIKE', "%{$search}%")
        ;
    }

    public function scopeFilterUsers($query)
    {
        foreach (Auth::user()->roles as $role) {
            if ($role->name === 'Супер Адміністратор') {
                return $query;
            } elseif ($role->name === 'Адміністратор') {
                return $query->when(Auth::user()->battalion, static function ($query, $battalion) {
                    $query->where('battalion', $battalion);
                })->
                when(Auth::user()->company, static function ($query, $company) {
                    $query->where('company', $company);
                })->
                when(Auth::user()->platoon, static function ($query, $platoon) {
                    $query->where('platoon', $platoon);
                });
            }
        }

    }

    public function getCount()
    {
        return $this->count();
    }

    public function getNotActivatedCount()
    {
        return $this->where('is_activated', 'Ні')->get()->count();
    }

    public function getRoleAdminCount()
    {
        return $this->role('Адміністратор')->get()->count();
    }

    public function getRoleUserCount()
    {
        return $this->role('Користувач')->count();
    }

    public function incidents()
    {
        return $this->hasMany(Incident::class);
    }

    public function mainPageIncidents()
    {
        return $this->hasMany(Incident::class);
    }

    public function feedbacks()
    {
        return $this->hasMany(Feedback::class);
    }

    public function formation()
    {
        return $this->belongsTo(Formation::class);
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }

//    public function chatActions()
//    {
//        return $this->hasMany(ChatAction::class);
//    }
}
