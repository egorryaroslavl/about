<?php

	namespace Egorryaroslavl\About;

	use Illuminate\Support\ServiceProvider;

	class AboutServiceProvider extends ServiceProvider
	{

		public function boot()
		{
			$this->loadViewsFrom( __DIR__ . '/views', 'about' );
			$this->loadRoutesFrom( __DIR__ . '/routes.php' );
			$this->publishes( [ __DIR__ . '/views' => resource_path( 'views/admin/about' ) ], 'about' );
			$this->publishes( [ __DIR__ . '/config/about.php' => config_path( '/admin/about.php' ) ], 'config' );
			$this->publishes( [
				__DIR__ . '/migrations/2017_06_16_200517_create_about_table.php' => base_path( 'database/migrations/2017_06_16_200517_create_about_table.php' )
			], '' );


		}

		public function register()
		{

			$this->app->make( 'Egorryaroslavl\About\AboutController' );
			$this->mergeConfigFrom( __DIR__ . '/config/about.php', 'about' );
		}

	}