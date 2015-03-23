<?php
namespace Intersvyaz\Behavior;

use CActiveRecord;
use CActiveRecordBehavior;
use CException;

/**
 * Поведение к модели для хранения "старых" аттрибутов модели.
 *
 * Пример получения старого значения модели:
 * <pre>
 * $model->oldAttributes['STATE']
 * $model->old->STATE
 * </pre>
 *
 * Пример проверки модификации аттрибутов:
 * <pre>
 * $model->isModified('STATE')
 * $model->isModified(['STATE', 'MARK'])
 * </pre>
 *
 * @property-read object $old
 * @property-read array $oldAttributes
 */
class OldAttributesBehavior extends CActiveRecordBehavior
{
	/**
	 * Список полей в виде строки или массива, которые нужно запоминать.
	 * Если не указан при конфигурации, то запоминаются все поля.
	 * @var string|array
	 */
	public $fields;

	/**
	 * Список полей в виде строки или массива, которые НЕ нужно запоминать.
	 * @var string|array
	 */
	public $exclude;
	/**
	 * Старые значения полей.
	 * @var array
	 */
	protected $_oldAttributes;

	/**
	 * Старые значения полей для доступа через объект.
	 * @var object
	 */
	protected $_oldObject;

	/**
	 * @param CActiveRecord $owner The component that this behavior is to be attached to.
	 * @throws CException
	 */
	public function attach($owner)
	{
		if (property_exists($owner, 'old')) {
			throw new CException(get_class($owner) . ' already have $old property!');
		}

		if (property_exists($owner, 'oldAttributes')) {
			throw new CException(get_class($owner) . ' already have $oldAttributes property!');
		}

		parent::attach($owner);
	}

	/**
	 * @inheritdoc
	 */
	public function afterFind($event)
	{
		/** @var CActiveRecord $model */
		$model = $event->sender;

		if ($model->hasAttribute('old')) {
			throw new CException('ActiveRecord already have "old" table column!');
		}

		if ($model->hasAttribute('oldAttributes')) {
			throw new CException('ActiveRecord already have "oldAttributes" table column!');
		}

		if (is_string($this->fields))
			$this->fields = explode(',', $this->fields);

		if (is_string($this->exclude))
			$this->exclude = explode(',', $this->exclude);

		$this->_oldAttributes = $model->getAttributes($this->fields);

		if (is_array($this->exclude)) {
			foreach ($this->exclude as $excludeField) {
				unset($this->_oldAttributes[$excludeField]);
			}
		}

		$this->_oldObject = (object)$this->_oldAttributes;
	}

	/**
	 * Метод для получения массива старых аттрибутов.
	 * @return array
	 */
	public function getOldAttributes()
	{
		return $this->_oldAttributes;
	}

	/**
	 * Метод для получения старых аттрибутов через объект.
	 * Пример использования:
	 * <pre>
	 * $model->old->STATE
	 * </pre>
	 * @return object
	 */
	public function getOld()
	{
		return $this->_oldObject;
	}

	/**
	 * Проверка модификации значений аттрибутов модели.
	 * Сравниваются значения аттрибутов полученных при загрузке модели из базы и текущие значения.
	 * @param array|string $attributes Проверяемые аттрибуты. Возможно передать один аттрибут или массив аттрибутов.
	 * @return bool
	 */
	public function isModified($attributes)
	{
		if (is_string($attributes)) {
			$attributes = [$attributes];
		}

		foreach ($attributes as $attribute) {
			if ($this->owner->$attribute != $this->_oldObject->$attribute)
				return true;
		}

		return false;
	}
}
