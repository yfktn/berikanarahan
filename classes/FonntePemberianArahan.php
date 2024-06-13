<?php namespace Yfktn\BerikanArahan\Classes;

use Log;
use Queue;
use Yfktn\BerikanArahan\Models\PersonilDitugaskan;
use Yfktn\ToolsKu\Classes\Fonnte;

class FonntePemberianArahan
{
    public static function personilDipilih(PersonilDitugaskan $personil)
    {
        $personilDitugaskanId = $personil->id;
        $arahanId = $personil->arahan->id;
        Queue::push(function($job) use($personilDitugaskanId, $arahanId) {
            $personilDitugaskan = PersonilDitugaskan::whereHas('arahan', function($q) use($arahanId) {
                    $q->where('id', $arahanId);
                })
                ->with(['personil', 'arahan.berdasarkan'])
                ->find($personilDitugaskanId);
            $tugas = empty($personilDitugaskan->tugas)? 'bertugas': $personilDitugaskan->tugas;
            $target = $personilDitugaskan->personil->contact_number;
            $arahan = strip_tags($personilDitugaskan->arahan->arahan);
            $deadline = $personilDitugaskan->arahan->deadline;
            $atasTugas = $personilDitugaskan->arahan->berdasarkan->getLabel();
            //Log::debug('Pemberian arahan ' . $tugas . ' ' . $target . ' ' . $arahan . ' ' . $atasTugas . ' ' . $deadline);
            $pesan = "Anda ditugaskan untuk menangani *{$atasTugas}*, untuk: *{$tugas}* dengan arahan: *{$arahan}*.";
            if(!empty($deadline)) {
                $pesan .= " Deadline: *{$deadline}*";
            }
            (new Fonnte)->sendMessage($target, $pesan);
            $job->delete();
        });
    }

    public static function personilDikeluarkan(PersonilDitugaskan $personil)
    {

    }
}