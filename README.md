# yii-old-attribute-behavior

[![Latest Stable Version](https://poser.pugx.org/intersvyaz/yii-old-attribute-behavior/v/stable.svg)](https://packagist.org/packages/intersvyaz/yii-old-attribute-behavior) [![Total Downloads](https://poser.pugx.org/intersvyaz/yii-old-attribute-behavior/downloads.svg)](https://packagist.org/packages/intersvyaz/yii-old-attribute-behavior) [![Latest Unstable Version](https://poser.pugx.org/intersvyaz/yii-old-attribute-behavior/v/unstable.svg)](https://packagist.org/packages/intersvyaz/yii-old-attribute-behavior) [![License](https://poser.pugx.org/intersvyaz/yii-old-attribute-behavior/license.svg)](https://packagist.org/packages/intersvyaz/yii-old-attribute-behavior)

Example usage:
```php
class User extends CActiveRecord
{
  /**
   * @inheritdoc
   */
	public function behaviors()
	{
		return array_merge(
			parent::behaviors(),
			[
				'oldBehavior' => ['class' => Intersvyaz\Behavior\OldAttributesBehavior::class],
			]
		);
	}
	
	/**
	 * @inheritdoc
	 */
	protected function afterSave()
	{
	  if ($this->isModified('username')) {
	      // some your logic
	      $oldValue = $this->old->username;
	      // or
	      $oldValue = $this->oldAttributes['username'];
	      // new value in $this->username
	  }
	  parent::afterSave();
	}
}
```
