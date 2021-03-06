<?php

namespace hrzg\widget\models\crud;

use dmstr\db\traits\ActiveRecordAccessTrait;
use Faker\Provider\DateTime;
use hrzg\widget\models\crud\base\Widget as BaseWidget;
use hrzg\widget\Module;
use hrzg\widget\widgets\Cell;
use yii\behaviors\TimestampBehavior;
use yii\db\Expression;
use yii\helpers\ArrayHelper;

/**
 * Class WidgetContent
 * @package hrzg\widget\models\crud
 */
class WidgetContent extends BaseWidget
{
    /**
     * Virtual attribute generated from "domain_id"_"access_domain".
     *
     * @var string
     */
    public $name_id;

    /**
     * Timezone that will be used to correct dates.
     *
     * @var string
     */
    public $timezone;

    /**
     * @inheritdoc
     * @return array
     */
    public function behaviors()
    {
        return ArrayHelper::merge(
            parent::behaviors(),
            [
                'timestamp' => [
                    'class' => TimestampBehavior::className(),
                    'value' => new Expression('NOW()'),
                ],
                'audit' => [
                    'class' => 'bedezign\yii2\audit\AuditTrailBehavior'
                ]
            ]
        );
    }

    public function init()
    {
        parent::init();

        if ($this->timezone === null) {
            $this->timezone = \Yii::$app->getModule('widgets')->timezone;
        }
    }

    /**
     * @inheritdoc
     */
    public function afterFind()
    {
        parent::afterFind();
        $this->setNameId($this->domain_id.'_'.$this->access_domain);

        // correct dates by timezone
        if($this->publish_at) {
            $dateByTimeZone = new \DateTime($this->publish_at, new \DateTimeZone($this->timezone));
            $this->publish_at = $dateByTimeZone->format('Y-m-d H:i');
        }
        if($this->expire_at) {
            $dateByTimeZone = new \DateTime($this->expire_at, new \DateTimeZone($this->timezone));
            $this->expire_at = $dateByTimeZone->format('Y-m-d H:i');
        }
    }

    /**
     * Get virtual name_id.
     *
     * @return string
     */
    public function getNameId()
    {
        return $this->name_id;
    }

    /**
     * Generate and Set virtual attribute name_id.
     *
     * @param mixed $name_id
     */
    public function setNameId($name_id)
    {
        $this->name_id = $name_id;
    }

    /**
     * @inheritdoc
     * @return array
     */
    public function rules()
    {
        return ArrayHelper::merge(
            parent::rules(),
            [
                ['rank', 'default', 'value' => 'a-'.dechex(date('U'))],
                [
                    'domain_id',
                    'default',
                    'value' => uniqid()
                ],
                [
                    'access_domain',
                    'default',
                    'value' => function () {
                        return mb_strtolower(\Yii::$app->language);
                    }
                ],
                ['access_domain', 'validateAccessDomain'],
                [
                    [
                        'access_read',
                        'access_update',
                        'access_delete',
                    ],
                    'default',
                    'value' => self::$_all
                ],
                [['publish_at', 'expire_at'], 'default', 'value' => null],
                [['publish_at', 'expire_at'], 'date', 'format' => 'yyyy-MM-dd HH:mm'],
                [ 'expire_at', 'compare', 'compareAttribute' => 'publish_at', 'operator' => '>', 'type' => 'datetime'],
            ]
        );
    }

    /**
     * Check if user tries to save / update records to another language
     * 'widget_copy' permission required
     *
     * @param $attribute
     */
    public function validateAccessDomain($attribute)
    {
        if ($this->attributes[$attribute] !== \Yii::$app->language && php_sapi_name() !== 'cli') {
            if (! \Yii::$app->user->can(Module::COPY_ACCESS_PERMISSION, ['route' => true])) {
                $errorMsg = \Yii::t('widgets', 'You are not allowed to copy widgets between languages');
                \Yii::$app->session->setFlash('error', $errorMsg);
                $this->addError($attribute, $errorMsg);
            }
        }
    }

    /**
     * Global route needs empty request param
     * @param bool $insert
     *
     * @return bool
     */
    public function beforeSave($insert)
    {
        if (parent::beforeSave($insert)) {

            if ($this->route === Cell::GLOBAL_ROUTE) {
                $this->request_param = Cell::EMPTY_REQUEST_PARAM;
            }

            // ensure lowercase language id
            $this->access_domain = mb_strtolower($this->access_domain);

            return true;
        } else {
            return false;
        }
    }

    /**
     * @return array
     */
    public static function optsAccessDomain()
    {
        $availableLanguages = [];
        foreach (\Yii::$app->urlManager->languages as $availablelanguage) {
            $availableLanguages[mb_strtolower($availablelanguage)] = mb_strtolower($availablelanguage);
        }
        return $availableLanguages;
    }

    /**
     * @return array
     */
    public static function optsStatus()
    {
        return [
            0 => \Yii::t('widgets', 'Offline'),
            1 => \Yii::t('widgets', 'Online'),
        ];
    }

    /**
     * @return array
     */
    public static function optsWidgetTemplateId()
    {
        return ArrayHelper::map(WidgetTemplate::find()->orderBy('name')->all(), 'id', 'name');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getTemplate()
    {
        return $this->hasOne(WidgetTemplate::className(), ['id' => 'widget_template_id']);
    }

    /**
     * @return string
     */
    public function getViewFile()
    {
        $file = '/'.\Yii::getAlias('@runtime').'/'.md5($this->template->twig_template).'.twig';
        file_put_contents($file, $this->template->twig_template);

        return $file;
    }
}
