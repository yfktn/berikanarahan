<?php namespace Yfktn\BerikanArahan\Classes;

use Event;
use Illuminate\Contracts\Container\BindingResolutionException;
use Log;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Container\ContainerExceptionInterface;

/**
 * Fungsi dari class ini adalah untuk mendapatkan layanan yang harus dijadikan sebagai bagian 
 * dari proses untuk mendapatkan arahan!
 * @package Yfktn\BerikanArahan\Classes
 */
class ProsesLayananBelumDiarahkan
{

    /**
     * Dapatkan jumlah arahan yang belum diarahkan
     * @return int<0, \max> 
     * @throws BindingResolutionException 
     * @throws NotFoundExceptionInterface 
     * @throws ContainerExceptionInterface 
     */
    public function getJumlahBelumDiarahkan(): int
    {
        $pilihanTrigger = $this->getProsesLayananBelumDiarahkan();
        return count($pilihanTrigger);
    }

    /**
     * Lakukan pengumpulan terhadap berbagai model yang dicatatakan sebagai layanan untuk diberikan
     * proses arahannya. Untuk ini, masing-masing harus mengembalikan hasil dari event berupa
     * array assoc dengan format sebagai berikut:
     * - pada bagian index berisikan pola <id>|<string namespace model>
     * - pada bagian nilai berisikan string label
     * Sebagai contoh: 
     * Event::listen('yfktn.berikanarahan.layananbelumdiarahkan', function() {
     *      return ['1|\Foo\Bar\Models\SuratMasuk' => 'Surat Masuk Dinas PU Nomor ABC'];   
     * }) 
     * @return array 
     */
    public function getProsesLayananBelumDiarahkan(): array
    {
        $results = Event::fire('yfktn.berikanarahan.layananbelumdiarahkan');
        // remove empty array
        $results = array_filter($results);
        // ada beberapa kembalian dari event itu multiarray
        $returnedResult = [];
        foreach($results as $r) {
            $returnedResult = array_merge($returnedResult, $r);
        }
        // Log::debug($returnedResult);
        return $returnedResult;
    }

}