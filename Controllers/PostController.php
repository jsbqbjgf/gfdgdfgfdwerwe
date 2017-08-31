<?php

namespace Project1\Http\Controllers;

use Project1\Non;
use Project1\User;
use Illuminate\Http\Request;
use DB;
use Project1\Http\Requests;
use Project1\Http\Controllers\Controller;

mb_internal_encoding();

/**
 * Тестовый контроллер
 *
 *
 */
class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // $name = $request->input('name');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
      
//return view('welcome');  //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
		//
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }


	/**
     * Коды ошибок сервера
     *
     * @param  int     $code 		 - код состояния http 
	 * @param  string  $message 	 - описание ошибки
	 * @param  string  $message_code - код описания ошибки
	 *
     * @return string  $err			 - результат выполнения
     */
	public function err($code, $message, $message_code) {
		$err 				= new \stdClass();
		$err->code		 	= $code;
		$err->message		= $message;
		$err->message_code  = $message_code;
		return $err;
	}


	/**
     * Запрос на вывод таблицы c возможностью фильтрации
     *
	 * @param  int     $perpage - количество элементов на одной странице, необязательный параметр
     * @param  int     $id 	    - идентификатор  пользователя, необязательный параметр 
     * @param  string  $name 	- имя пользователя, необязательный параметр 
     * @param  string  $mobile  - номер мобильного телефона пользователя, необязательный параметр
     * @param  string  $phone 	- номер городского телефона пользователя, необязательный параметр
	 *
     * @return object  $view    - результат выполнения запроса на вывод таблицы
     * @return object  $filter  - результат выполнения выборки из таблицы
     */
	public function view(Request $request) {
		//Проверка запроса
		try {
			//Количество элементов на странице
		    $perpage=100; //regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/
		    $v = \Validator::make($request->all(), ['id' => 'integer', 
		        'name' => 'alpha',
		        'mobile' => 'integer', 'phone' => 'integer'
		    ]);
			//Метод для построения запросов
			$filter = Non::query();
			//Метод для разбиения страниц
			$view = Non::paginate($perpage);
			//Определение наличия требуемых переменных для выборки
			//с возвращением результата
			if ($request->has('perpage')) {
				$perpage=$request->perpage; 
				$view = Non::paginate($perpage);
				return $view;
			}
			if ($request->has('name')) {
				$filter->where('name', $request->name); 
				return json_encode($filter->get());
			}
			if ($request->has('id')) {  
				$filter->where('id', $request->id); 
				return json_encode($filter->get());
			}
			if ($request->has('mobile')) {
				$filter->where('mobile', $request->mobile); 
				return json_encode($filter->get());
			}
			if ($request->has('phone')) {
				$filter->where('phone', $request->phone); 
				return json_encode($filter->get());
			}
			//Возвращение результата
			return $view;		
		}
		//При обнаружении ошибки 
		//вызывается блок catch
		catch (\Exception $e) {
		    //Ошибка валидации
		    if ($v->fails()) {
		        return json_encode($this->err("400", $v->errors()->all(), ""));
		    }
			//Вывод ошибки
		    echo json_encode($this->err("404", $e->getMessage(), ""));
		}
	}


	/**
     * Запрос на удаление элементов из таблицы
     *
     * @param  int     $id 	- идентификатор пользователя, обязательный параметр
	 *
     * @return string  $del	- результат выполнения запроса
     */
	public function del(Request $request) {
		//Проверка запроса	
		try {
			//Передача значения параметра
			$id  = $request->id;
			//Валидация на наличие обязательного  параметра
			$v = \Validator::make($request->all(), ['id' => 'required|integer']);
			//Поиск требуемой строки
			$del = Non::findorfail($id);
			//Удаление
			$del->delete();
			//Возвращение результата
			return json_encode($del);
		}
		//При обнаружении ошибки 
		//вызывается блок catch
		catch (\Exception $e) {
			//Ошибка валидации
	   	 	if ($v->fails()) {
	   	 	    return json_encode($this->err("400", $v->errors()->all(), ""));
   			}
			//Вывод ошибки
			return json_encode($this->err("404", $e->getMessage(), ""));
		}
	}
	

	/**
     * Запрос на добавление элементов в таблицу
     *
     * @param  int      $id 	- идентификатор пользователя, обязательный параметр 
     * @param  string   $name 	- имя пользователя, обязательный параметр 
     * @param  string   $mobile - номер мобильного телефона пользователя, необязательный параметр
     * @param  string   $phone 	- номер городского телефона пользователя, необязательный параметр
	 *
     * @return object   $result	- результат выполнения запроса json
     */
	public function add(Request $request) {
		try {	   
			//Создание экземпляра модели			
			$add = new Non;
			$v = \Validator::make($request->all(), ['id' => 'required|integer', 
			    'name' => 'required|alpha',
			    'mobile' => 'integer', 'phone' => 'integer' 
			]);
			//Передача значений параметров
			$add->id	= $request->id;
			$add->name	= $request->name;
			//Валидация на наличие обязательных  параметров
			if ($request->has('mobile')) $add->mobile= $request->mobile;
			if ($request->has('phone')) $add->phone = $request->phone;
			//Создание новой записи в БД
			$add->save();
			//Возвращение результата
			$r = $add->where('id',$add->id)->get();
			return json_encode($r[0]);
		}
		//При обнаружении ошибки 
		//вызывается блок catch
		catch (\Exception $e) {
			//Ошибка валидации
	   	 	if ($v->fails()) {
	   	 	    return json_encode($this->err("400", $v->errors()->all(), ""));
   			}
			//Вывод ошибки
			return json_encode($this->err("404", $e->getMessage(), ""));
		}
	}

	/**
     * Запрос на изменение элементов в таблице
     *
     * @param  int      $id 	- идентификатор  пользователя, обязательный параметр 
     * @param  string   $name 	- имя пользователя, необязательный параметр 
     * @param  string   $mobile - номер мобильного телефона пользователя, необязательный параметр
     * @param  string   $phone 	- номер городского телефона пользователя, необязательный параметр
	 *
     * @return object   $result	- результат выполнения запроса
     */
	public function upd(Request $request) {
		//Проверка запроса	
		try {
			//Передача значения параметра id
			$id     = $request->id;
			//Валидация на наличие обязательного  параметра
			$v = \Validator::make($request->all(), ['id' => 'required|integer', 
			    'name' => 'alpha',
			    'mobile' => 'integer', 'phone' => 'integer'
			]);
			//Поиск требуемой строки по id
			$upd	= Non::findOrFail($id);
			//Определение наличия требуемых переменных для внесения изменений в таблицу 
			if ($request->has('name')) {
    			$upd->name   = $request->name;
			}
			if ($request->has('mobile')) {
    			$upd->mobile = $request->mobile; 
			}
			if ($request->has('phone')) {
    			$upd->phone  = $request->phone; 
			}
			//Сохранение изменений
			$upd->update();
			$r = $upd->where('id',$upd->id)->get();
			//Возвращение результата
			return json_encode($r[0]);
		}
		//При обнаружении ошибки 
		//вызывается блок catch
		catch (\Exception $e) {
			//Ошибка валидации
	   	 	if ($v->fails()) {
	   	 	    return json_encode($this->err("400", $v->errors()->all(), ""));
       			 //return $v->errors()->all();
   			}
			//Вывод ошибки
			return json_encode($this->err("404", $e->getMessage(), ""));
		}
	}

	/**
     * Запрос для фильтрации данных в таблице
     *
     * @param  int      $id 	- идентификатор  пользователя, необязательный параметр 
     * @param  string   $name 	- имя пользователя, необязательный параметр 
     * @param  string   $mobile - номер мобильного телефона пользователя, необязательный параметр
     * @param  string   $phone 	- номер городского телефона пользователя, необязательный параметр
	 *
     * @return object   $filter	- результат выполнения запроса
     */

	public function filter(Request $request) {
		//Проверка запроса	
		try {	

			$filter = Non::query();
			if ($request->has('name')) {
    			$filter->where('name', $request->name);
			}
			if ($request->has('id')) {
    			$filter->where('id', $request->id);
			}
			if ($request->has('mobile')) {
    			$filter->where('mobile', $request->mobile);
			}
			if ($request->has('phone')) {
 	   			$filter->where('phone', $request->phone);
			}

			return $filter->get();
		}
		catch (\Exception $e) {
			//Вывод ошибки
			echo json_encode($this->err("404", $e->getMessage(), ""));
		}
	}



//WHERE id IN (12,34) AND name IN («Kohn», «John», «Soho») OR last_visit <= «20-09-2017»

//?name[]=kohn & name[]=john & name[]=soho &
	
    /**
     * Запрос списка пользователей с использованием фильтра.
     *
     * @param  int     $id			- идентификатор или массив идентификаторов пользователей, необязательный параметр, 
     * @param  string  $lname		- логическое условие для фильтра name, необязательный параметр, по умолчанию 'AND'
     * @param  string  $name		- имя пользователя или массив пользователей, необязательный параметр 
	 * @param  string  $lvisit		- логическое условие для фильтра last_visit, необязательный параметр, по умолчанию 'AND'
 	 * @param  date    $last_visit	- последнее посещение пользователей, необязательный параметр
	 * @param  string  $сomparison	- оператор сравнения для last_visit, необязательный параметр, по умолчанию 'E'
	 *
     * @return string  $filter - результат выполнения запроса
     */

	public function tes(Request $request) {
		//Проверка запроса	
		try {		
		}
		catch (\Exception $e) {
		}

		$r = new \stdClass();
		$r->id		= $request->id;
		$r->page	= $request->page;
		$r->code	= $request->code;


		return json_encode($r);
	}

}

