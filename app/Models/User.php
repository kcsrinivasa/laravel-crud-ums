<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Carbon\Carbon;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [ 'name','email','password','doj','dol','stillWorking','profile', ];

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
    ];

    public function getExperienceAttribute(){

        $doj=Carbon::createFromFormat('Y-m-d H:s:i',($this->doj)?$this->doj:now());
        $dol=Carbon::createFromFormat('Y-m-d H:s:i',($this->dol)?$this->dol:now());

        return $doj->diffForHumans($dol, true, false, 2);

        // $days = $dol->diffInDays($doj);
        // $years = $dol->diffInYears($doj);
        // $months = $dol->diffInMonths($doj);
    }
}
