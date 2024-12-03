<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class Staff extends Model
{
    use HasFactory, HasRoles, SoftDeletes;

	protected $guard_name = 'api';

    protected $guarded = ['id'];
	public $timestamps = false;

    public function company(): \Illuminate\Database\Eloquent\Relations\BelongsTo
	{
		return $this->belongsTo(Company::class);
	}

	public function user(): \Illuminate\Database\Eloquent\Relations\BelongsTo
	{
		return $this->belongsTo(User::class);
	}
}
