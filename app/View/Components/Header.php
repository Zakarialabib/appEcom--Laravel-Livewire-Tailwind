<?php

declare(strict_types=1);

namespace App\View\Components;

use Illuminate\View\Component;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;

class Header extends Component
{
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|Closure|string
     */
    public function render(): View|Factory
    {
        return view('components.header');
    }
}
