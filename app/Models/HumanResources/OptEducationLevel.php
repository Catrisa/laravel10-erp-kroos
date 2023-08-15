<?php

namespace App\Models\HumanResources;

use Illuminate\Database\Eloquent\Factories\HasFactory;
// use Illuminate\Database\Eloquent\Model;
use App\Models\Model;
use App\Models\Staff;
// db relation class to load
// use Illuminate\Database\Eloquent\Relations\HasOne;
// use Illuminate\Database\Eloquent\Relations\HasOneThrough;
// use Illuminate\Database\Eloquent\Relations\HasOneOrMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
// use Illuminate\Database\Eloquent\Relations\HasManyThrough;
// use Illuminate\Database\Eloquent\Relations\BelongsTo;
// use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class OptEducationLevel extends Model
{
    use HasFactory;
	// protected $connection = 'mysql';
	protected $table = 'option_education_levels';

	/////////////////////////////////////////////////////////////////////////////////////////////////////
	public function hasmanytaxexemptionpercentage(): HasMany
	{
		return $this->hasMany(\App\Models\HumanResources\HRStaffChildren::class, 'education_level_id');
	}
}
