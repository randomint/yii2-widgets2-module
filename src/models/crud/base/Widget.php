<?php
// This class was automatically generated by a giiant build task
// You should not change it manually as it will be overwritten on next build

namespace hrzg\widget\models\crud\base;

use dmstr\db\traits\ActiveRecordAccessTrait;
use Yii;

/**
 * This is the base-model class for table "app_hrzg_widget".
 *
 * @property int $id
 * @property string $status
 * @property string $widget_template_id
 * @property string $default_properties_json
 * @property string $domain_id
 * @property string $container_id
 * @property string $rank
 * @property string $route
 * @property string $request_param
 * @property string $access_owner
 * @property string $access_domain
 * @property string $access_read
 * @property string $access_update
 * @property string $access_delete
 * @property string $created_at
 * @property string $updated_at
 * @property string $aliasModel
 */
abstract class Widget extends \yii\db\ActiveRecord
{
    use ActiveRecordAccessTrait;

    /**
     * Enable access_domain access checks in ActiveRecordAccessTrait
     * @return array with access field names
     */
    public static function accessColumnAttributes()
    {
        return [
            'owner'  => 'access_owner',
            'read'   => 'access_read',
            'update' => 'access_update',
            'delete' => 'access_delete',
            'domain' => 'access_domain',
        ];
    }

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%hrzg_widget_content}}';
    }

    /**
     * Alias name of table for crud viewsLists all Area models.
     * Change the alias name manual if needed later.
     *
     * @return string
     */
    public function getAliasModel($plural = false)
    {
        if ($plural) {
            return Yii::t('widgets', 'Widgets');
        } else {
            return Yii::t('widgets', 'Widget');
        }
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'widget_template_id', 'container_id', 'route'], 'required'],
            [['default_properties_json'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['status'], 'string', 'max' => 32],
            [['container_id', 'route'], 'string', 'max' => 128],
            [['domain_id'], 'string', 'max' => 64],
            [['widget_template_id'], 'integer'],
            [['rank', 'access_owner'], 'string', 'max' => 11],
            [['request_param', 'access_read', 'access_update', 'access_delete'], 'string', 'max' => 255],
            [['access_domain'], 'string', 'max' => 8],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('widgets', 'ID'),
            'status' => Yii::t('widgets', 'Status'),
            'widget_template_id' => Yii::t('widgets', 'Template'),
            'default_properties_json' => Yii::t('widgets', 'Widget Properties'),
            'domain_id' => Yii::t('widgets', 'Domain ID'),
            'name_id' => Yii::t('widgets', 'Name ID'),
            'container_id' => Yii::t('widgets', 'Container ID'),
            'rank' => Yii::t('widgets', 'Rank'),
            'route' => Yii::t('widgets', 'Route'),
            'request_param' => Yii::t('widgets', 'Request Param'),
            'access_owner' => Yii::t('widgets', 'Access Owner'),
            'access_domain' => Yii::t('widgets', 'Access Domain'),
            'access_read' => Yii::t('widgets', 'Access Read'),
            'access_update' => Yii::t('widgets', 'Access Update'),
            'access_delete' => Yii::t('widgets', 'Access Delete'),
            'created_at' => Yii::t('widgets', 'Created At'),
            'updated_at' => Yii::t('widgets', 'Updated At'),
        ];
    }
}
