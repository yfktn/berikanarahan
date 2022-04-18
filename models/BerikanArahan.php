<?php namespace Yfktn\BerikanArahan\Models;

use Backend\Facades\BackendAuth;
use Model;
use Db;
use Illuminate\Support\Facades\Log;

/**
 * Model
 */
class BerikanArahan extends Model
{
    use \October\Rain\Database\Traits\Validation;
    
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
     * @return void 
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
}
