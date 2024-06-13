<?php namespace Yfktn\BerikanArahan;

use Event;
use System\Classes\PluginBase;
use Yfktn\BerikanArahan\Classes\ProsesLayananBelumDiarahkan;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
    }

    public function registerSettings()
    {
    }

    public function registerReportWidgets()
    {
        return [
            \Yfktn\BerikanArahan\ReportWidgets\ArahanBelumDiArahkan::class => [
                'label' => 'Layanan Belum Diarahkan',
                'context' => 'dashboard',
                'permissions' => [],
            ],
            \Yfktn\BerikanArahan\ReportWidgets\RekapanStatus::class => [
                'label' => 'Rekap Status',
                'context' => 'dashboard',
                'permissions' => [],
            ]
        ];   
    }

    public function boot()
    {
        Event::listen('backend.menu.extendItems', function($manager) {
            $manager->getMainMenuItem('Yfktn.BerikanArahan', 'main-menu-beriarah')
                ->counter((new ProsesLayananBelumDiarahkan)->getJumlahBelumDiarahkan());
        });            
    }

}
