<?php namespace Yfktn\BerikanArahan\Controllers;

use Backend\Classes\Controller;
use BackendMenu;
use BackendAuth;
use Exception;
use Event;
use Illuminate\Support\Facades\Log;
use PhpParser\Node\Stmt\Return_;
use Yfktn\BerikanArahan\Models\BerikanArahan as ModelsBerikanArahan;
use Yfktn\BerikanArahan\Models\Pesan;
use Yfktn\BerikanArahan\Widgets\Rptnya;
use Yfktn\DiscussWidget\Widgets\DiscussWidget;
/**
 * Apabila haknya sebagai yfktn.berikan_arahan.manajer maka berhak untuk semua.
 * Jika dia berhak untuk yfktn.berikan_arahan.penunjukan_personil maka berhak
 * untuk memilih personil yang ingin dipilih sebagai bagian dari penyelesaian 
 * tugas ini.
 * 
 * @package Yfktn\BerikanArahan\Controllers
 */
class BerikanArahan extends Controller
{
    public $implement = [
        'Backend\Behaviors\ListController',
        'Backend\Behaviors\FormController',
        'Backend\Behaviors\RelationController'
    ];
    
    public $listConfig = 'config_list.yaml';
    public $formConfig = 'config_form.yaml';
    public $relationConfig = 'config_relation.yaml';


    // public $requiredPermissions = [
    //     'yfktn.berikan_arahan.manajer',
    //     'yfktn.berikan_arahan.penunjukan_personil'
    // ];

    public function __construct()
    {
        parent::__construct();
        BackendMenu::setContext('Yfktn.BerikanArahan', 'main-menu-beriarah');

        $rptArahan = new Rptnya($this);
        $rptArahan->alias = 'rptnya';
        $rptArahan->bindToController();

        $discussWidget = new DiscussWidget($this);
        $discussWidget->alias = 'discussWidget';
        $discussWidget->bindToController();

        \Event::listen('backend.form.extendFields', function ($widget) {
            // Only for the User controller
            if (!$widget->getController() instanceof BerikanArahan) {
                return;
            }
        
            // Only for the User model
            if (!$widget->model instanceof Pesan) {
                return;
            }

            if(\BackendAuth::getUser()->hasPermission(['yfktn.berikan_arahan.manajer'])) {
                return;
            }

            if(!$widget->model->exists) {
                return;
            }
    
            if($widget->model->personil_id != BackendAuth::getUser()->id) {
                $widget->fields['pesan']['disabled'] = true;
                $pesanField = $widget->getField('pesan');
                $pesanField->disabled = true;
                $daftarDokumenLampiranField = $widget->getField('daftarDokumenLampiran');
                $daftarDokumenLampiranField->disabled = true;
                $widget->previewMode = true;
                // \Log::info($pesanField);
                // $widget->addFields([
                //     'pesan' => [
                //         'label'   => 'Keterangan',
                //         'comment' => 'Ketikkan keterangan terkait progress penanganan terbaru.',
                //         'type'    => 'richeditor',
                //         'span'    => 'full',
                //         'disabled'=> 'true',
                //     ]
                // ]);
                // $fields->daftarDokumenLampiran->readOnly = true;
            }
        
            // Add an extra birthday field
        
            // Remove a Surname field
        });
    }

    public function update($recordId, $context = null)
    {
        $this->bodyClass = 'compact-container';
        $this->widget->discussWidget->setOwner($recordId, ModelsBerikanArahan::class);
        return $this->asExtension('FormController')->update($recordId, $context);
    }

    /**
     * Siapkan user yang membuat ini!
     * @param mixed $model 
     * @return void 
     */
    public function formBeforeCreate($model)
    {
        $model->pengarah_id = $this->user->id;
    }

    /**
     * Tampilkan informasi yang menjadi sumber referensi adanya arahan.
     * Pastikan bahwa model yang dijadikan sebagai sumber referensi melakukan
     * implementasi terhadap InterfaceTampilanSingkatTrigger!
     * @return mixed 
     * @throws Exception 
     */
    public function onTampilkanInformasiTrigger()
    {
        $errorStr = "";
        try {
            $idnya = post('idnya');
            $modelnya = post('modelnya');
            $modelnyaClass = str_replace('.', "\\", $modelnya);
            $modelObject = new $modelnyaClass;
            $this->vars['record'] = $modelObject->find($idnya);
        } catch(Exception $e) {
            $errorStr = $e->getMessage();
            $this->vars['errorStr'] = $errorStr;
            Log::error($errorStr . "\n" . $e->getTraceAsString());
        }
        return
            isset($errorStr[0]) ?
                $this->makePartial('informasitriggererror'):
                $this->makePartial('informasitrigger');
    }

    /**
     * Jangan enabled kan beberapa form tapi bisa untuk form yang lain!
     * Sesuaikan dengan siapa yang login!
     * @param mixed $form 
     * @param mixed $fields 
     * @return void 
     */
    public function formExtendFields($form, $fields)
    {
        if(!$this->user->hasAccess('yfktn.berikan_arahan.manajer')) {
            $fields['berdasarkan_str']->disabled = true;
            $fields['arahan']->disabled = true;
            $fields['deadline']->disabled = true;
        }
    }

    public function listExtendQuery($query)
    {
        if(!BackendAuth::getUser()->hasPermission(['yfktn.berikan_arahan.manajer'])) {
            return $query->tampilkanDaftarArahanUntukOrangYangLoginIniSaja();
        }
        return $query;
    }

    public function relationExtendConfig($config, $field, $model)
    {
        if(BackendAuth::getUser()->hasPermission(['yfktn.berikan_arahan.manajer'])) {
            return;
        }
        // Make sure the model and field matches those you want to manipulate
        if (!$model instanceof ModelsBerikanArahan) { // || $field != 'myField')
            return;
        }

        if(
            !BackendAuth::getUser()->hasPermission(['yfktn.berikan_arahan.penunjukan_personil'])
            ) {
                if($field == 'personilDitugaskan') {
                    // readonly saja untuk personil ditugaskan
                    $config->readOnly = true;
                } elseif($field == 'progressPenanganan') {
                    $config->view['toolbarButtons'] = [
                        'create' => 'Catatkan Progress'
                    ];
                    $config->view['showCheckboxes'] = false;
                    // $config->view['recordOnClick'] = 'javascript:return false';
                    // $config->view['recordUrl'] = 'yfktn/berikanarahan/pesan/preview/:id';
                }
            }
    }
}
