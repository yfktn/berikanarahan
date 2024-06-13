<?php namespace Yfktn\BerikanArahan\ReportWidgets;

use Backend\Classes\ReportWidgetBase;

class RekapanStatus extends ReportWidgetBase
{
    public function render()
    {
        return $this->makePartial("widget");
    }
}