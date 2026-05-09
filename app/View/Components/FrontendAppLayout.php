<?php

namespace App\View\Components;

use Illuminate\View\Component;
use Illuminate\View\View;

class FrontendAppLayout extends Component
{
    public ?string $title;

    /**
     * @var array<string, mixed>
     */
    public array $seoMeta;

    /**
     * Create a new component instance.
     *
     * The SEO metadata array is intentionally optional so existing frontend pages
     * keep their current behavior while public marketplace pages can pass dynamic
     * metadata into the shared head partial.
     *
     * @param  array<string, mixed>  $seoMeta
     */
    public function __construct(?string $title = null, array $seoMeta = [])
    {
        $this->title = $title;
        $this->seoMeta = $seoMeta;
    }

    /**
     * Get the view / contents that represents the component.
     */
    public function render(): View
    {
        return view('frontend.layouts.app');
    }
}
