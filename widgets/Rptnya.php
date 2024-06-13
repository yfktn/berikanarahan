<?php namespace Yfktn\BerikanArahan\Widgets;

use Backend\Classes\WidgetBase;
use Yfktn\BerikanArahan\Classes\ProsesLayananBelumDiarahkan;
use Yfktn\BerikanArahan\Classes\RekapPerstatusan;
use Yfktn\BerikanArahan\Models\BerikanArahan;
use Yfktn\BerikanArahan\ReportWidgets\RekapanStatus;

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
        $this->vars['belumDiArahkan'] = (new ProsesLayananBelumDiarahkan)->getJumlahBelumDiarahkan();
        $this->vars['berproses'] = (new RekapPerstatusan())->getJumlahStatusLagiProses();
    }
}
