<?php namespace Yfktn\BerikanArahan\Models;

use Model;

/**
 * Model
 */
class Narahubung extends Model
{
    use \October\Rain\Database\Traits\Validation;
    

    /**
     * @var string The database table used by the model.
     */
    public $table = 'yfktn_berikanarahan_narahubung';

    /**
     * @var array Validation rules
     */
    public $rules = [
    ];
}
