<?php

namespace App\View\Components;

use Illuminate\View\Component;

class TextInput extends Component
{
    public $disabled;

    /**
     * Create a new component instance.
     */
    public function __construct($disabled = false)
    {
        $this->disabled = $disabled;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.text-input');
    }
}
