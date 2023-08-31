<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    protected $fillable = ['name'];

    public static function getSkills()
    {
    	return Skill::select('id','name')->get();
    }

    public static function addSkill($skill_name)
    {
    	$add_skill = Skill::create(['name' => $skill_name]);
    	if ($add_skill)
    		return true;
    	else
    		return false;
    }
}
