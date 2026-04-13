<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes; // Tambahkan ini
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Tambahkan ini

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * Atribut yang dapat diisi secara massal (Mass Assignment).
     * Disesuaikan dengan kolom di migration Anda.
     */
    protected $fillable = [
        'username',
        'password',
        'fullname',
        'email',
        'phone',
        'role_id',
        'created_at',
        'updated_at'
    ];

    protected $dates = ['deleted_at'];
    /**
     * Atribut yang harus disembunyikan saat serialisasi (JSON).
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Relasi ke Model Role (Setiap user punya satu role).
     */
    public function role(): BelongsTo
    {
        return $this->belongsTo(Role::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Casting atribut.
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}