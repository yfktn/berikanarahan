<?php namespace Yfktn\BerikanArahan\Controllers;

use Backend\Classes\Controller;
use BackendMenu;

class Pesan extends Controller
{
    public $implement = [        'Backend\Behaviors\ListController',        'Backend\Behaviors\FormController'    ];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Yfktn.BerikanArahan', 'main-menu-beriarah', 'sm-pesan');
    }

    public function onPerpesananForm()
    {
        $this->asExtension('FormController')->create();

        return $this->makePartial('perpesanan_form');
    }
}
