<?php namespace Yfktn\BerikanArahan\Classes;

use Illuminate\Contracts\Container\BindingResolutionException;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Container\ContainerExceptionInterface;

/**
 * Fungsi dari class ini adalah untuk mendapatkan jumlah arahan yang belum diarahkan
 * 
 * @package Yfktn\BerikanArahan\Classes
 */
class JumlahArahanBelumDiarahkan
{
    /**
     * Dapatkan jumlah arahan yang belum diarahkan
     * @return int<0, \max> 
     * @throws BindingResolutionException 
     * @throws NotFoundExceptionInterface 
     * @throws ContainerExceptionInterface 
     */
    public static function getJumlah(): int
    {
        $ba = new \Yfktn\BerikanArahan\Models\BerikanArahan;
        $pilihanTrigger = $ba->loadPilihanTriggerNya(null, '', '');
        return count($pilihanTrigger);
    }

}