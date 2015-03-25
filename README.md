# yii-old-attribute-behavior

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
