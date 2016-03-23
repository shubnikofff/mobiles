<?php
/**
 * mobiles
 * Created: 22.03.16 14:48
 * @copyright Copyright (c) 2016 OSKR NIAEP
 */

namespace app\modules\directory\models;

use yii\base\Model;
use yii\data\ArrayDataProvider;
use yii\web\UploadedFile;


/**
 * @author Shubnikov Alexey <a.shubnikov@niaep.ru>
 *
 * ImportForm
 * @property ArrayDataProvider $notImported
 */
class ImportForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $datafile;
    /**
     * @var ArrayDataProvider
     */
    private $_notImported;

    public function init()
    {
        parent::init();

        $this->_notImported = new ArrayDataProvider([
            'pagination' => false,
            'sort' => false
        ]);
    }


    public function rules()
    {
        return [
            ['datafile', 'file', 'skipOnEmpty' => false],
        ];
    }

    public function attributeLabels()
    {
        return [
            'datafile' => 'Файл с данными'
        ];
    }

    public function attributeHints()
    {
        return [
            'datafile' => 'Файл должен быть в формате CSV, сохраненный в UTF-8 кодировке. Формат данных: табельный номер, ФИО, должность'
        ];
    }

    public function import()
    {
        if ($this->validate()) {

            if (($handle = fopen($this->datafile->tempName, "r")) !== false) {

                $notImported = [];

                while (($data = fgetcsv($handle)) !== false) {

                    /** @var $employee Employee */
                    if (($employee = Employee::findOne(['organic_number' => $data[0]])) !== null) {
                        $employee->post = $data[2];
                        $employee->save();
                    } else {
                        $notImported[] = [
                            'personnel_number' => $data[0],
                            'name' => $data[1],
                            'post' => $data[2],
                            'division1' => $data[3],
                            'division2' => $data[4],
                            'division3' => $data[5],
                            'group' => $data[6],
                        ];
                    }

                }

                fclose($handle);
                $this->_notImported->allModels = $notImported;
                return;
            }
            throw new \Exception('Предоставленный файл невозможно открыть');
        }
    }

    /**
     * @return ArrayDataProvider
     */
    public function getNotImported()
    {
        return $this->_notImported;
    }

}