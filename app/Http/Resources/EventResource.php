<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EventResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'                    => $this->id,
            'event_type_id'         => $this->event_type_id,
            'name'                  => $this->name,
            'slug'                  => $this->slug,
            'tagline'               => $this->tagline,
            'description'           => $this->description,
            'logo'                  => $this->logo,
            'image'                 => $this->image,
            'banner_image'          => $this->banner_image,
            'video_teaser_url'      => $this->video_teaser_url,
            'location_map_url'      => $this->location_map_url,
            'start_date'            => $this->start_date,
            'end_date'              => $this->end_date,
            'start_time'            => $this->start_time,
            'end_time'              => $this->end_time,
            'venue'                 => $this->venue,
            'organizer_name'        => $this->organizer_name,
            'organizer_brand'       => $this->organizer_brand,
            'purchase_deadline'     => $this->purchase_deadline,
            'total_capacity'        => $this->total_capacity,
            'age_restriction'       => $this->age_restriction,
            'event_type'            => $this->event_type,
            'terms_and_conditions'  => $this->terms_and_conditions,
            'added_by'              => $this->added_by,
            'updated_by'            => $this->updated_by,
            'created_at'            => $this->created_at,
            'updated_at'            => $this->updated_at,

            // Optional: eager loaded relationship
            // 'event_type_data'       => new EventTypeResource($this->whenLoaded('eventType')),
        ];
    }
}
