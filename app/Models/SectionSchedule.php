<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SectionSchedule extends Model
{
    protected $fillable = ['section_id', 'day', 'start_time', 'end_time', 'room'];

    public function section()
    {
        return $this->belongsTo(Section::class);
    }
}