<?php namespace Yfktn\BerikanArahan\Models;

use Backend\Facades\BackendAuth;
use Model;
use Db;
use Flash;
use Illuminate\Support\Facades\Log;
use ApplicationException;

/**
 * Model
 */
class BerikanArahan extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
    const STATUS_MENUNGGU_MULAI = 'MENUNGGU_MULAI';
    const STATUS_LAGI_PROSES = 'LAGI_PROSES';
    const STATUS_SELESAI = 'SELESAI';
    const STATUS_DITUTUP = 'DITUTUP';

    /**
     * @var string The database table used by the model.
     */
    public $table = 'yfktn_berikanarahan_';

    public $morphTo = [
        'berdasarkan' => [],
    ];

    public $hasMany = [
        'personilDitugaskan' => [
            'Yfktn\BerikanArahan\Models\PersonilDitugaskan', 
            'key' => 'arahan_id'
        ],
        'progressPenanganan' => [
            'Yfktn\BerikanArahan\Models\Pesan', 
            'key' => 'arahan_id'
        ],
        'narahubung' => [
            'Yfktn\BerikanArahan\Models\Narahubung', 
            'key' => 'arahan_id'
        ],
    ];

    public $attachMany = [
        'daftarDokumenHasil' => 'System\Models\File'
    ];

    /**
     * @var array Validation rules
     */
    public $rules = [
        'arahan' => 'required',
        'berdasarkan_str' => 'required'
    ];

    /**
     * Lakukan loading terhadap pilihan item yang menjadi trigger munculnya arahan.
     * Di sini ada untuk SuratMasuk, PermintaanLayanan
     * @param mixed $fieldName 
     * @param mixed $value 
     * @param mixed $formData 
     */
    public function loadPilihanTriggerNya($value, $fieldName, $formData)
    {
        $sumberTriggerSql = config('yfktn.berikanarahan::sumber_trigger');
        if(count($sumberTriggerSql) <= 0) {
            return [];
        }
        $queryStr = implode(" UNION ", $sumberTriggerSql);
        // hati-hati dengan tanda backslash maka saat dimasukkan ke dalam 
        // sistem sql, tambahkan dulu dengan backslash
        $hasilQuery = Db::select(str_replace("\\", "\\\\", $queryStr));
        $hasil = [];
        foreach($hasilQuery as $h) {
            $hasil[$h->id . '|' . $h->model] = $h->label;
        }
        if(!is_null($value)) { // bila ini adalah data yang tidak baru!
            // dapatkan yang terpilih
            [ $theid, $themodel ] = explode("|", $value);
            $obj = (new $themodel)->find($theid);
            $hasil[$value] = $obj->getLabel();
        }
        return $hasil;
    }

    public function getStatusOptions()
    {
        return config('yfktn.berikanarahan::status');
    }

    public function getLabelStatusAttribute()
    {
        $a = $this->getStatusOptions();
        return $a[$this->status] ?? '';
    }

    /**
     * Sebelum di simpan ayo lakukan setting nilai trigger!
     * @return void 
     */
    public function beforeSave()
    {
        // check apakah terdapat perubahan pada trigger untuk arahannya?
        if($this->isDirty('berdasarkan_str')) {
            // untuk format trigger antara id dan model dipisahkan oleh tanda |
            [$this->berdasarkan_id, $ber_type]  = explode("|", $this->berdasarkan_str);
            $this->berdasarkan_type = str_replace(".", "\\", $ber_type); // ingat bahwa kita mengganti tanda / dengan titik
        }
    }

    public function beforeDelete()
    {
        if(!BackendAuth::getUser()->hasPermission(['yfktn.berikan_arahan.manajer'])) {
            throw new ApplicationException('Hanya manager yang bisa menghapus!');
            return false;
        }
    }

    public function scopeTampilkanDaftarArahanUntukOrangYangLoginIniSaja($query)
    {
        $loggedUserId = BackendAuth::getUser()->id;
        return $query->whereHas('personilDitugaskan', function($query) use($loggedUserId) {
            $query->where('personil_id', $loggedUserId);
        });
    }
}
