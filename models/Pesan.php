<?php namespace Yfktn\BerikanArahan\Models;

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
        'daftarDokumen' => 'System\Models\File'
    ];
}
