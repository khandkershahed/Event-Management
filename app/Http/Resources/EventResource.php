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
            'id'                     => $this->id,
            'category_id'            => $this->category_id,
            'sub_category_id'        => $this->sub_category_id,
            'sub_sub_category_id'    => $this->sub_sub_category_id,
            'author_id'              => $this->author_id,
            'title'                  => $this->title,
            'bangla_title'           => $this->bangla_title,
            'slug'                   => $this->slug,
            'summary'                => $this->summary,
            'bangla_summary'         => $this->bangla_summary,
            'content'                => $this->content,
            'bangla_content'         => $this->bangla_content,
            'thumbnail'              => $this->thumbnail ?? null,
            'banner_image'           => $this->banner_image ?? null,
            'video_url'              => $this->video_url,
            'tags'                   => $this->tags,
            'meta_title'             => $this->meta_title,
            'meta_description'       => $this->meta_description,
            'meta_keywords'          => $this->meta_keywords,
            'type'                   => $this->type,
            'is_featured'            => $this->is_featured,
            'is_most_read'           => $this->is_most_read,
            'is_breaking'            => $this->is_breaking,
            'is_trending'            => $this->is_trending,
            'show_on_homepage'       => $this->show_on_homepage,
            'show_in_slider'         => $this->show_in_slider,
            'status'                 => $this->status,
            'published_at'           => $this->published_at,
            'author'                 => $this->author,
            'view_count'             => $this->view_count,
            'share_count'            => $this->share_count,
            'comment_count'          => $this->comment_count,
            'created_by'             => $this->created_by,
            'updated_by'             => $this->updated_by,
            'created_at'             => $this->created_at,
            'updated_at'             => $this->updated_at,
            'category_name'          => optional($this->category)->name,
            'category_bangla_name'   => optional($this->category)->bangla_name,
            'subCategory_name'       => optional($this->subCategory)->name,
            'subCategory_bangla_name' => optional($this->subCategory)->bangla_name,
        ];
    }
}
