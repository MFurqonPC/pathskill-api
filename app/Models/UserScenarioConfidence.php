<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserScenarioConfidence extends Model
{
    use HasFactory;

    protected $table = 'user_scenario_confidence';

    protected $fillable = ['user_id', 'scenario_confidence_item_id', 'confidence_level'];

    public function scenarioConfidenceItem(): BelongsTo
    {
        return $this->belongsTo(ScenarioConfidenceItem::class);
    }
}