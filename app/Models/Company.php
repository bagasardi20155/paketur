<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class Company extends Model
{
    use HasFactory, HasRoles, SoftDeletes;

    protected $guard_name = 'api';
    protected $guarded = ['id'];

    public function staff(): \Illuminate\Database\Eloquent\Relations\HasMany
	{
		return $this->hasMany(User::class);
	}
}
