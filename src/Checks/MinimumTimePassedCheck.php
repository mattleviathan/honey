<?php


namespace Lukeraymonddowning\Honey\Checks;


use Illuminate\Support\Facades\Crypt;
use Lukeraymonddowning\Honey\InputNameSelectors\InputNameSelector;

class MinimumTimePassedCheck implements Check
{
    private InputNameSelector $inputNameSelector;

    public function __construct(InputNameSelector $inputNameSelector)
    {
        $this->inputNameSelector = $inputNameSelector;
    }

    public function passes($data): bool
    {
        return $this->getTimePassed($data) >= $this->getConfiguredMinimumTime();
    }

    protected function getTimePassed($data)
    {
        return microtime(true) - $this->getDecryptedValue($data);
    }

    protected function getDecryptedValue($data)
    {
        return Crypt::decrypt($data[$this->inputNameSelector->getTimeOfPageLoadInputName()]);
    }

    protected function getConfiguredMinimumTime()
    {
        return config('honey.minimum_time_passed');
    }
}