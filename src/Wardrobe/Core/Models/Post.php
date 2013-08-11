<?php namespace Wardrobe\Core\Models;

use Config, Cache;
use Carbon\Carbon;

class Post extends BaseModel {

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'posts';

	/**
	 * The attributes that are mass assignable.
	 *
	 * @var array
	 */
	protected $fillable = array('title', 'slug', 'content', 'active', 'publish_date', 'user_id');

	/**
	 * Tags Relationship
	 *
	 * @return Relationship
	 */
	public function tags()
	{
		return $this->hasMany('Wardrobe\Core\Models\Tag', 'post_id');
	}

	/**
	 * User relationship
	 *
	 * @return Relationship
	 */
	public function user()
	{
		return $this->belongsTo('Wardrobe\Core\Models\User');
	}

	/**
	 * Get the content parsed into html
	 *
	 * @return string
	 */
	public function getParsedContentAttribute()
	{
		if (Config::get('wardrobe.cache'))
		{
			$content = $this->attributes['content'];
			return Cache::rememberForever('post-'.$this->attributes['id'], function() use ($content)
			{
				return md($content);
			});
		}
		return md($this->attributes['content']);
	}

	/**
	 * Get the atom date for atom feeds
	 * @return DateTime
	 */
	public function getAtomDateAttribute()
	{
		$dt = Carbon::createFromFormat('Y-m-d H:i:s', $this->attributes['publish_date']);

		return $dt->toATOMString();
	}

	/**
	 * Get the atom date for rss feeds
	 * @return DateTime
	 */
	public function getRssDateAttribute()
	{
		$dt = Carbon::createFromFormat('Y-m-d H:i:s', $this->attributes['publish_date']);

		return $dt->toRSSString();
	}

	public function getDates()
	{
	    return array('created_at', 'updated_at', 'publish_date');
	}

}
