<?php
/**
 * Created by PhpStorm.
 * User: Tanakorn
 * Date: 13/11/2561
 * Time: 20:00
 */
namespace mcomscience\data;

use Yii;
use yii\base\BaseObject;
use yii\helpers\ArrayHelper;
use yii\base\InvalidConfigException;
use yii\i18n\Formatter;

class Column extends BaseObject
{
    public $attribute;

    public $value;

    public $title;

    public $encodeLabel = true;

    public $content;

    public $format = 'text';

    public $options = [];

    public $formatter;

    public $component;

    public function init()
    {
        parent::init();
        if ($this->formatter === null) {
            $this->formatter = Yii::$app->getFormatter();
        } elseif (is_array($this->formatter)) {
            $this->formatter = Yii::createObject($this->formatter);
        }
        if (!$this->formatter instanceof Formatter) {
            throw new InvalidConfigException('The "formatter" property must be either a Format object or a configuration array.');
        }
        if ($this->attribute === null) {
            throw new InvalidConfigException('The "data" property must be set.');
        }
    }

    public function getDataCellValue($model, $key, $index)
    {
        if ($this->value !== null) {
            if (is_string($this->value)) {
                return ArrayHelper::getValue($model, $this->value);
            }
            return call_user_func($this->value, $model, $key, $index, $this);
        } elseif ($this->attribute !== null) {
            return ArrayHelper::getValue($model, $this->attribute);
        }
        return null;
    }

    public function renderDataCell($model, $key, $index)
    {
        return $this->renderDataCellContent($model, $key, $index);
    }

    protected function renderDataCellContent($model, $key, $index)
    {
        if ($this->content === null) {
            return $this->formatter->format($this->getDataCellValue($model, $key, $index), $this->format);
        }
        return parent::renderDataCellContent($model, $key, $index);
    }
}