<?php namespace Yfktn\BerikanArahan\Classes;

use Db;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Container\BindingResolutionException;
use Psr\Container\NotFoundExceptionInterface;
use Psr\Container\ContainerExceptionInterface;
use InvalidArgumentException;

class RekapPerstatusan
{
    /**
     * Kembalikan rekapan statusnya, dengan kode status dan jumlah.
     * @return Collection 
     * @throws BindingResolutionException 
     * @throws NotFoundExceptionInterface 
     * @throws ContainerExceptionInterface 
     * @throws InvalidArgumentException 
     */
    protected function getRekapPerstatusan()
    {
        $cacheLong = config('app.debug') ? 0 : 300;
        $cacheKey = 'yfktn.berikanarahan.rekapperstatusan';
        return cache()->remember($cacheKey, $cacheLong, function () {
            $statusnya = Db::table('yfktn_berikanarahan_')
                ->groupBy('status')
                ->selectRaw('status, count(*) as jumlah')
                ->get();
            $kembalian = [];
            foreach ($statusnya as $status) {
                $kembalian[$status->status] = $status->jumlah;
            }
            return $kembalian;
        });
    }

    public function getJumlahStatusLagiProses()
    {
        $kembalian = $this->getRekapPerstatusan();
        return $kembalian['LAGI_PROSES'];
    }
}