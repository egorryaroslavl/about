<?php


	namespace Egorryaroslavl\About\Models;

	use Illuminate\Database\Eloquent\Model;


	class AboutModel extends Model
	{
		protected $table = 'about';

		protected $fillable = [
			'name',
			'alias',
			'description',
			'short_description',
			'pos',
			'public',
			'anons',
			'hit',
			'h1',
			'metatag_title',
			'metatag_description',
			'metatag_keywords' ];

		protected $casts = [
			'public'  => 'boolean',
			'anons'   => 'boolean',
			'hit'     => 'boolean',
		];

	}