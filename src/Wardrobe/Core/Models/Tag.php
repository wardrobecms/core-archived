<?php namespace Wardrobe\Core\Models;

class Tag extends BaseModel {

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'tags';

	/**
	 * Turn off timestamps
	 *
	 * @var boolean
	 */
	public $timestamps = false;

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = array('post_id', 'tag');

	/**
	 * Post relationship
	 *
	 * @return Relationship
	 */
	public function posts()
	{
		return $this->belongsTo('Wardrobe\Core\Models\Post');
	}

}
