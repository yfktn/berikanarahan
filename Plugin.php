<?php namespace Yfktn\BerikanArahan;

use Event;
use System\Classes\PluginBase;
use Yfktn\BerikanArahan\Classes\JumlahArahanBelumDiarahkan;

class Plugin extends PluginBase
{
    public function registerComponents()
    {
    }

    public function registerSettings()
    {
    }

    public function boot()
    {
        Event::listen('backend.menu.extendItems', function($manager) {
            $manager->getMainMenuItem('Yfktn.BerikanArahan', 'main-menu-beriarah')
                ->counter(JumlahArahanBelumDiarahkan::getJumlah());
        });
            
    }

}
