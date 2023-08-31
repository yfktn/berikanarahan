<?php namespace Yfktn\BerikanArahan\Widgets;

use Backend\Classes\WidgetBase;
use Yfktn\BerikanArahan\Classes\JumlahArahanBelumDiarahkan;
use Yfktn\BerikanArahan\Models\BerikanArahan;

class Rptnya extends WidgetBase
{
    /**
     * @var string A unique alias to identify this widget.
     */
    protected $defaultAlias = 'rptnya';

    public function render()
    {
        $this->siapkanVariable();
        return $this->makePartial('rptnya');
    }

    public function siapkanVariable()
    {
        $this->vars['belumDiArahkan'] = JumlahArahanBelumDiarahkan::getJumlah();
    }
}
