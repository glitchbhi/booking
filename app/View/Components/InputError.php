<?php

namespace App\View\Components;

use Illuminate\View\Component;

class InputError extends Component
{
    public $messages;

    /**
     * Create a new component instance.
     */
    public function __construct($messages = [])
    {
        $this->messages = $messages;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render()
    {
        return view('components.input-error');
    }
}
