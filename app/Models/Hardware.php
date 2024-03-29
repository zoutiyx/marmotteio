<?php

namespace App\Models;

use App\Traits\HasTeam;
use App\Traits\HasUniqueIdentifier;
use App\Traits\Quantifiable;
use App\Traits\Tenantable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

/**
 * @method static count()
 * @method static create(array $array)
 *
 * @property mixed $hardware_model
 * @property mixed $name
 * @property mixed $id
 */
class Hardware extends Model
{
    use HasFactory, HasTeam, HasUniqueIdentifier, Quantifiable, Tenantable;

    protected $fillable = [
        'serial_number',
        'order_number',
        'team_id',
        'hardware_model_id',
        'hardware_status_id',
        'department_id',
        'supplier_id',
        'location_id',
        'expected_checkin_date',
        'purchase_date',
        'end_of_life_date',
        'purchase_cost',
        'notes',
        'requestable',
        'name',
        'team_id',
        'files',
        'notes',
        'qr_code',
    ];

    protected $casts = [
        'files' => 'array',
    ];

    public function scopeWithStatus($query, $statusName)
    {
        return $query->whereHas('hardware_status', function ($query) use ($statusName) {
            $query->where('name', '=', $statusName);
        });
    }

    public function hardware_model(): BelongsTo
    {
        return $this->belongsTo(HardwareModel::class);
    }

    public function department(): BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function hardware_status(): BelongsTo
    {
        return $this->belongsTo(HardwareStatus::class);
    }

    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }

    public function components(): BelongsToMany
    {
        return $this->belongsToMany(Component::class)->withTimestamps()->using(ComponentHardware::class)->withPivot('team_id', 'id', 'checked_in_at', 'checked_out_at');
    }

    public function licences(): BelongsToMany
    {
        return $this->belongsToMany(Licence::class)->withTimestamps()->using(HardwareLicence::class)->withPivot('team_id', 'id', 'checked_in_at', 'checked_out_at');
    }

    public function people(): BelongsToMany
    {
        return $this->belongsToMany(Person::class)->withTimestamps()->using(HardwarePerson::class)->withPivot('team_id', 'id', 'checked_in_at', 'checked_out_at');
    }
}
