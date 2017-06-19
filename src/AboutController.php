<?php

	namespace Egorryaroslavl\About;

	use App\Http\Controllers\Controller;
	use Illuminate\Http\Request;
	use Illuminate\Validation\Rule;
	use Egorryaroslavl\About\Models\AboutModel;

	class AboutController extends Controller
	{


		function messages()
		{
			$strLimit = config( 'admin.settings.text_limit.short_description', 300 );
			return [
				'name.required'         => 'Поле "Имя" обязятельно для заполнения!',
				'alias.required'        => 'Поле "Алиас" обязятельно для заполнения!',
				'name.unique'           => 'Значение поля "Имя" не является уникальным!',
				'alias.unique'          => 'Значение поля "Алиас" не является уникальным!',
				'description.required'  => 'Поле "Текст" обязятельно для заполнения!',
				'short_description.max' => 'Поле "Краткий текст" не должно быть более ' . $strLimit . ' символов!',

			];

		}

		public function index()
		{


			/*  Если записи в БД нет, создаём пустую  */
			$count = \DB::table( 'about' )->count();
			if( $count === 0 ){
				\DB::table( 'about' )->insert( [ 'id' => 1, 'name' => '', 'alias' => '' ] );
			}

			$data = AboutModel::where( 'id', 1 )->first();


			$data->table = 'about';
			$breadcrumbs = '<div class="row wrapper border-bottom white-bg page-heading"><div class="col-lg-12"><h2>О компании</h2><ol class="breadcrumb"><li><a href="/admin">Главная</a></li><li class="active"><a href="/admin/about">О компании</a></li></ol></div></div>';


			return view( 'about::index',
				[
					'data'        => $data,
					'breadcrumbs' => $breadcrumbs
				] );


		}


		public function create()
		{
			$data        = new AboutModel();
			$data->act   = 'about-store';
			$data->table = 'about';

			$breadcrumbs = '<div class="row wrapper border-bottom white-bg page-heading"><div class="col-lg-12"><h2>О компании</h2><ol class="breadcrumb"><li><a href="/admin">Главная</a></li><li class="active"><a href="/admin/about">О компании</a></li><li><strong>Создание новой записи</strong></li></ol></div></div>';

			return view( 'about::form', [ 'data' => $data, 'breadcrumbs' => $breadcrumbs ] );


		}


		public function edit( $id = 1 )
		{


			/*  Если записи в БД нет, создаём пустую  */
			$count = \DB::table( 'about' )->count();
			if( $count === 0 ){
				\DB::table( 'about' )->insert( [ 'id' => 1, 'name' => '', 'alias' => '' ] );
			}

			$data = AboutModel::where( 'id', 1 )->first();


			$data->table = 'about';
			$data->act   = 'about-update';


			$breadcrumbs = '<div class="row wrapper border-bottom white-bg page-heading"><div class="col-lg-12"><h2>О компании</h2><ol 
class="breadcrumb"><li><a href="/admin">Главная</a></li><li 
class="active"><a href="/admin/about">О компании</a></li><li>Редактирование <strong>[
 <a href="/about/' . $data->alias . '" style="color:blue" title="Смотреть на пользовательской части">' . $data->name . ' <img src="/_admin/img/extlink.png" alt="" 
 style="margin:0"></a> ]</strong></li></ol></div></div>';

			return view( 'about::form', [
				'data'        => $data,
				'breadcrumbs' => $breadcrumbs,
			] );
		}


		public function update( Request $request )
		{



			if( mb_strlen( $request->short_description ) > config( 'admin.settings.text_limit.short_description', 300 ) ){
				$request->short_description = substr( $request->short_description, 0, config( 'admin.settings.text_limit.short_description', 300 ) );
			}



			$strLimit = config( 'admin.settings.text_limit.short_description' );
			$direct   = isset( $request->submit_button_stay ) ? 'stay' : 'back';

			$v = \Validator::make( $request->all(), [
				'name' => [
					'required',
					Rule::unique( 'about' )->ignore( $request->id ),
					'max:255'
				],

				'alias'             => [
					'required',
					Rule::unique( 'about' )->ignore( $request->id ),
					'max:255'
				],
				'description'       => 'required',
				'short_description' => 'max:' . config( 'admin.settings.text_limit.short_description', 300 ),


			], $this->messages() );


			if( $v->fails() ){
				return redirect( 'admin/about/' . $request->id . '/edit' )
					->withErrors( $v )
					->withInput();
			}

			$about                      = AboutModel::find( $request->id );
			$about->name                = $request->name;
			$about->alias               = $request->alias;
			$about->description         = $request->description;
			$about->short_description   = str_limit( trim( $request->short_description ), $strLimit, '...' );
			$about->public              = isset( $request->public ) ? $request->public : 0;
			$about->anons               = isset( $request->anons ) ? $request->anons : 0;
			$about->hit                 = isset( $request->hit ) ? $request->hit : 0;
			$about->h1                  = $request->h1;
			$about->metatag_title       = $request->metatag_title;
			$about->metatag_description = $request->metatag_description;
			$about->metatag_keywords    = $request->metatag_keywords;
			$about->save();

			\Session::flash( 'message', 'Запись обновлена!' );


			if( $direct == 'back' ){
				return redirect( url( '/admin/about' ) );
			}

			if( $direct == 'stay' ){
				return redirect()->back();
			}
		}


		public function destroy( $id )
		{

			$about = AboutModel::find( $id );
			$about->delete();
			return redirect()->back();

		}

		public function translite( Request $request )
		{

			$dictionary = array(
				"А" => "a",
				"Б" => "b",
				"В" => "v",
				"Г" => "g",
				"Д" => "d",
				"Е" => "e",
				"Ж" => "zh",
				"З" => "z",
				"И" => "i",
				"Й" => "y",
				"К" => "K",
				"Л" => "l",
				"М" => "m",
				"Н" => "n",
				"О" => "o",
				"П" => "p",
				"Р" => "r",
				"С" => "s",
				"Т" => "t",
				"У" => "u",
				"Ф" => "f",
				"Х" => "h",
				"Ц" => "ts",
				"Ч" => "ch",
				"Ш" => "sh",
				"Щ" => "sch",
				"Ъ" => "",
				"Ы" => "yi",
				"Ь" => "",
				"Э" => "e",
				"Ю" => "yu",
				"Я" => "ya",
				"а" => "a",
				"б" => "b",
				"в" => "v",
				"г" => "g",
				"д" => "d",
				"е" => "e",
				"ж" => "zh",
				"з" => "z",
				"и" => "i",
				"й" => "y",
				"к" => "k",
				"л" => "l",
				"м" => "m",
				"н" => "n",
				"о" => "o",
				"п" => "p",
				"р" => "r",
				"с" => "s",
				"т" => "t",
				"у" => "u",
				"ф" => "f",
				"х" => "h",
				"ц" => "ts",
				"ч" => "ch",
				"ш" => "sh",
				"щ" => "sch",
				"ъ" => "y",
				"ы" => "y",
				"ь" => "",
				"э" => "e",
				"ю" => "yu",
				"я" => "ya",
				"-" => "_",
				" " => "_",
				"," => "_",
				"." => "_",
				"?" => "",
				"!" => "",
				"«" => "",
				"»" => "",
				":" => "",
				'ё' => "e",
				'Ё' => "e",
				"*" => "",
				"(" => "",
				")" => "",
				"[" => "",
				"]" => "",
				"<" => "",
				">" => ""
			);
			$string     = preg_replace( '/[^\w\s]/u', ' ', $request->alias_source );
			$string     = mb_strtolower( strtr( strip_tags( trim( $string ) ), $dictionary ) );
			$alias      = preg_replace( '/[_]+/', '_', $string );
			return json_encode( [ 'alias' => $alias ] );
		}


		public static function changestatus( Request $request )
		{
			$sql = "
			UPDATE `" . $request->table . "` 
			SET `" . $request->field . "` = NOT `" . $request->field . "` WHERE id =" . $request->id;

			$res = \DB::update( $sql );

			if( $res > 0 ){
				$current = $request->value > 0 ? '0' : '1';
				echo json_encode( [ 'error' => 'ok', 'message' => $current ] );
			} else{
				echo json_encode( [ 'error' => 'error', 'message' => '' ] );
			}

		}

		public function reorder( Request $request )
		{


			if( isset( $request->sort_data ) ){

				$id        = array();
				$table     = $request->table;
				$sort_data = $request->sort_data;

				parse_str( $sort_data );

				$count = count( $id );
				for( $i = 0; $i < $count; $i++ ){

					\DB::update( 'UPDATE `' . $table . '` SET `pos`=' . $i . ' WHERE `id`=? ', [ $id[ $i ] ] );

				}

			}
		}


	}