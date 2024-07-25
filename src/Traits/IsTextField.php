<?php

namespace LVP\Traits;

trait IsTextField
{
    private $_min = 0;
    private $_max = 255;
    public function regex(string $value)
    {
        $this->_rules = array_merge($this->_rules, ['regex:' . $value]);
        return $this;
    }

    public function maxlength(int $value)
    {
        $this->_rules = array_merge($this->_rules, ['max:' . $value]);
        return $this;
    }

    public function minlength(int $value)
    {
        $this->_rules = array_merge($this->_rules, ['min:' . $value]);
        return $this;
    }

    public function placeholder(string $value)
    {

        $this->_placeholder = $value;
        return $this;
    }
    /**
     * Summary of getRules
     * @param mixed $action
     * @return array
     */
    public function getRules($action)
    {
        $rules = $this->_rules;

        if (!$this->canfillOn($action)) {
            // for ($i = 0; $i < count($this->_rules); $i++) {
            //     dump($this->_field, $rules[$i], 'min:' . $this->_min);
            //     if ($rules[$i] == 'required' || $rules[$i] == 'min:' . $this->_min || $rules[$i] == 'max:' . $this->_max) {
            //         unset($rules[$i]);
            //     }
            // }
            // unset($rules['min:' . $this->_min]);
            // unset($rules['max:' . $this->_max]);
            // $rules = array_diff($rules, ['required']);
            // $rules = array_diff($rules, ['min:' . $this->_min]);
            // $rules = array_diff($rules, ['max:' . $this->_max]);
            $rules = [];
        }
        // if ($this->_field == 'password') {
        //     dd($rules, $this->_field, !$this->canfillOn($action));
        // }
        return $rules;
    }
}
