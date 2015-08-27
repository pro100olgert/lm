<?php

namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Inflector;
use ReflectionClass;

/**
 * This is the model class for table "block".
 *
 * @property integer $id
 * @property string $name
 * @property string $content
 * @property integer $area
 * @property integer $weight
 */
class Block extends ActiveRecord
{
    const AREA_BOTTOM         = 1;
    const AREA_FOOTER         = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'block';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'content'], 'required'],
            [['content'], 'string'],
            [['area', 'weight'], 'integer'],
            [['name'], 'string', 'max' => 100],
            [['weight'], 'default', 'value' => 0],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Заголовок',
            'content' => 'Содержимое',
            'area' => 'Зона',
            'weight' => 'Вес',
        ];
    }

    /**
     * Get list of statuses for creating dropdowns
     *
     * @return array
     */
    public static function areaDropdown()
    {
        // get data if needed
        static $dropdown;
        if ($dropdown === null) {

            // create a reflection class to get constants
            $reflClass = new ReflectionClass(get_called_class());
            $constants = $reflClass->getConstants();

            // check for status constants (e.g., STATUS_ACTIVE)
            foreach ($constants as $constantName => $constantValue) {

                // add prettified name to dropdown
                if (strpos($constantName, "AREA_") === 0) {
                    $prettyName               = str_replace("AREA_", "", $constantName);
                    $prettyName               = Inflector::humanize(strtolower($prettyName));
                    $dropdown[$constantValue] = $prettyName;
                }
            }
        }

        return $dropdown;
    }

    /**
     * Get human area name
     *
     * @return array
     */
    public static function getHumanAreaName($area)
    {
        $areas = self::areaDropdown();
        foreach ($areas as $id => $name) {
            if($id == $area) return $name;
        }
        return '-';
    }
}
