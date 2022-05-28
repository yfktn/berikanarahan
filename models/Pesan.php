<?php namespace Yfktn\BerikanArahan\Models;

use Backend\Facades\BackendAuth;
use Model;

/**
 * Model
 */
class Pesan extends Model
{
    use \October\Rain\Database\Traits\Validation;
    

    /**
     * @var string The database table used by the model.
     */
    public $table = 'yfktn_berikanarahan_pesan';

    /**
     * @var array Validation rules
     */
    public $rules = [
        'pesan' => 'required'
    ];

    public $belongsTo = [
        'arahan' => ['Yfktn\BerikanArahan\Models\BerikanArahan', 'key' => 'arahan_id'],
        'personil' => ['Backend\Models\User', 'key' => 'personil_id']
    ];

    public $attachMany = [
        'daftarDokumenLampiran' => 'System\Models\File'
    ];

    public function beforeCreate()
    {
        // pastikan sebelum ini dibuat baru, tambahkan kode personil
        // ini merupakan user backend yang login
        $this->personil_id = BackendAuth::getUser()->id;
    }
}
