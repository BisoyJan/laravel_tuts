<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class RadioGroup extends Component
{
    /**
     * Create a new component instance.
     */
    public function __construct(public string $name, public array $options)
    {
        //
    }

    /**
     * Generate an array of options with their corresponding labels.
     *
     * If the options array is associative, it is returned as is.
     * If the options array is a list, a new array is created with the values
     * as both keys and values.
     *
     * @return array
     */
    public function optionsWithLabels(): array
    {
        // Check if the options array is associative.
        return array_is_list($this->options) ?
            // If it's a list, create a new array with the values as both keys and values.
            array_combine($this->options, $this->options) :
            // If it's associative, return the array as is.
            $this->options;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.radio-group');
    }
}
