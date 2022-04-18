<?php
namespace Yfktn\BerikanArahan\Classes;

interface InterfaceTampilanSingkatTrigger 
{
    /**
     * Misalnya sebuah SuratMasuk, maka indek assoc akan merujuk pada tujuannya dan
     * nilai arraynya merujuk pada nilai yang ditampilkannya. Pada nilai bisa dimasukkan
     * tag HTML.
     * return [
     *   'Label' => 'Surat Masuk ABC',
     *   'Files'  => [
     *        'link/ke/filenya.docx'
     *      ]
     * ]
     * @return mixed 
     */
    public function getArrayAssocTampilanSingkat();
    public function getLabel();
}