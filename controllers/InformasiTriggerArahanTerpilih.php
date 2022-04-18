<?php namespace Yfktn\BerikanArahan\Controllers;
use Backend\Classes\Controller;

class InformasiTriggerArahanTerpilih extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function tampilkan($id, $model)
    {
        $modelLoc = str_replace(".", "\\", $model);
        $modelObj = new $modelLoc;
        $this->vars['tampilan'] = $modelObj->find($id)->getArrayAssocTampilanSingkat();
    }
}