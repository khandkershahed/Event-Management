<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SeatingPlan extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * Corresponds to the properties of the Symfony entity.
     */
    protected $fillable = [
        'venue_id',
        'name',
        'slug',
        'design',
    ];

    /**
     * The attributes that should be cast.
     * The 'design' column is JSON, so we cast it to a PHP array automatically.
     */
    protected $casts = [
        'design' => 'array',
    ];

    // RELATIONSHIPS
    // These replace the Doctrine @ORM\ManyToOne and @ORM\OneToMany annotations.

    /**
     * A SeatingPlan belongs to a Venue.
     * Corresponds to: @ORM\ManyToOne(targetEntity="Venue", inversedBy="seatingPlans")
     */
    public function venue(): BelongsTo
    {
        return $this->belongsTo(Venue::class);
    }

    /**
     * A SeatingPlan can be used in many EventDates.
     * Corresponds to: @ORM\OneToMany(targetEntity="EventDate", mappedBy="seatingPlan")
     * Note: You will need to create the EventDate model for this to work.
     */
    // public function eventDates(): HasMany
    // {
    //     return $this->hasMany(EventDate::class);
    //     // Uncomment the line above once you have an EventDate model.
    // }


    // BUSINESS LOGIC METHODS
    // These methods are ported directly from your VenueSeatingPlan entity.

    /**
     * Calculates the total number of available seats in the entire plan.
     * This is used in the main listing to show the total capacity[cite: 41].
     */
    public function countTotalSeats(): int
    {
        $count = 0;
        foreach ($this->design['sections'] ?? [] as $section) {
            foreach ($section['rows'] ?? [] as $row) {
                $count += $this->getRowSeatsCount($row);
            }
        }
        return $count;
    }

    /**
     * Returns an array of section names and their respective seat counts.
     * This is used in the details modal on the index page[cite: 59].
     */
    public function getSectionsSeatsQuantityArray(): array
    {
        $sectionsSeatsQuantityArray = [];
        foreach ($this->design['sections'] ?? [] as $section) {
            $sectionsSeatsQuantityArray[$section['name']] = $this->getSectionSeatsCount($section);
        }
        ksort($sectionsSeatsQuantityArray);
        return $sectionsSeatsQuantityArray;
    }

    /**
     * Calculates the seat count for a single section.
     */
    public function getSectionSeatsCount(array $section): int
    {
        $count = 0;
        foreach ($section['rows'] ?? [] as $row) {
            $count += $this->getRowSeatsCount($row);
        }
        return $count;
    }

    /**
     * Calculates the seat count for a single row, accounting for disabled/hidden seats.
     * This is the core calculation logic from your entity.
     */
    public function getRowSeatsCount(array $row): int
    {
        $start = $row['seatsStartNumber'] ?? 0;
        $end = $row['seatsEndNumber'] ?? 0;
        $disabled = count($row['disabledSeats'] ?? []);
        $hidden = count($row['hiddenSeats'] ?? []);

        return (intval($end) - intval($start)) + 1 - $disabled - $hidden;
    }

    /**
     * Returns a sorted array of all section names.
     */
    public function getSectionsNamesArray(): array
    {
        $sectionsNamesArray = [];
        foreach ($this->design['sections'] ?? [] as $section) {
            $sectionsNamesArray[$section['name']] = $section['name'];
        }
        ksort($sectionsNamesArray);
        return $sectionsNamesArray;
    }
}
