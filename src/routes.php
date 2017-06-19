<?php


	/*=============  ABOUT  ==============*/

	Route::group( [ 'middleware' => 'web' ], function (){

		Route::get( '/admin/about', 'egorryaroslavl\about\AboutController@edit' );
		Route::get( '/admin/about/create', 'egorryaroslavl\about\AboutController@create' )->middleware( 'web' );
		Route::get( '/admin/about/{id}/edit', 'egorryaroslavl\about\AboutController@edit' )->middleware( 'web' );
		Route::get( '/admin/about/{id}/delete', 'egorryaroslavl\about\AboutController@destroy' )->middleware( 'web' );
		Route::post( '/admin/about/store', 'egorryaroslavl\about\AboutController@store' )->middleware( 'web' )->name('about-store');
		Route::post( '/admin/about/update', 'egorryaroslavl\about\AboutController@update' )->middleware( 'web' )->name('about-update');

		Route::post( '/translite', 'egorryaroslavl\about\AboutController@translite' )->middleware( 'web' )->name('translite');


		Route::post( '/changestatus', 'egorryaroslavl\about\AboutController@changestatus' )->middleware( 'web' )->name('changestatus');



	} );




	/*=============  /ABOUT  ==============*/