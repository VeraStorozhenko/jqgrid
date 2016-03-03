<?php

$servername		= "localhost";
$username  		= "mysql";
$parol			= "mysql";
$dbname     	= "myDB";
$table			= "count";

$q				= $_GET['q'];
$search 		= $_GET['_search'];

$page 			= $_GET['page'];  
$limit 			= $_GET['rows'];//Сколько строк мы хотим иметь в таблице - rowNum параметр 
$sidx 			= $_GET['sidx'];//Колонка для сортировки. Сначала sortname параметр, затем index из colModel 
$sord 			= $_GET['sord'];//Порядок сортировки. (=значению sortorder)

$searchField 	= $_GET['searchField'];
$searchString 	= $_GET['searchString'];
$searchOper		= $_GET['searchOper'];

$id 			= $_POST['id'];  
$date 			= $_POST['date'];
$amount 		= $_POST['amount'];
$tax 		    = $_POST['tax'];
$total			= $_POST['total'];
$note 			= $_POST['note'];
$oper 			= $_POST['oper'];

if(!$sidx) $sidx = 1; //Если колонка сортировки не указана, то будем сортировать по первой колонке.
	 
if($sqli = new mysqli($servername, $username, $parol, $dbname)){
	$sqli->query('SET NAMES utf8');//После подключения устанавливаем кодировку
	$result = $sqli->query("SELECT * FROM $table");
	$count = $result->num_rows; //Получили количество рядов
}else mysql_error();  

	// Вычисляем общее количество страниц.
	if( $count > 0 && $limit > 0) { 
	              $total_pages = ceil($count/$limit); 
	} else { 
	              $total_pages = 0; 
	} 
	 
	if ($page > $total_pages) $page=$total_pages;// Если запрашиваемый номер страницы больше общего количества страниц,то устанавливаем номер страницы в максимальный.
	$start = $limit*$page - $limit;//Вычисляем начальное смещение строк.
	if($start <0) $start = 0; //Если начальное смещение отрицательно,то устанавливаем его в 0.
	$i=0;

if($q == 2){ //Вывод  таблицы
	if($search == 'false'){ //Вывод всей таблицы
		$resAll = $sqli->query("SELECT * FROM $table ORDER BY $sidx $sord LIMIT $start , $limit");
		while($row = $resAll->fetch_array(MYSQL_BOTH)){
		    $responce->rows[$i]['id']=$row[id];
		    $responce->rows[$i]['cell']=array($row[id],$row[date],$row[amount],$row[tax],$row[total],$row[note]);
		    $i++;	    
		}
	}else {//Вывод части таблицы, удовлетворяющей условиям поиска
		$c = 0;
		if($searchOper == 'eq'){
			if($res_search = $sqli->query("SELECT * FROM $table WHERE $searchField = $searchString")){
				while ($row=$res_search->fetch_array(MYSQL_BOTH)){
					$responce->rows[$c]['id']=$row[id]; //correct
					$responce->rows[$c]['cell']=array($row[id],$row[date],$row[amount],$row[tax],$row[total],$row[note]); 
					$c++;
				};		
			}
		}else if($searchOper == 'lt'){
			if($res_search = $sqli->query("SELECT * FROM $table WHERE $searchField < $searchString")){
				while ($row=$res_search->fetch_array()){
					$responce->rows[$c][$searchField]=$row[$searchField];
					$responce->rows[$c]['cell']=array($row[id],$row[date],$row[amount],$row[tax],$row[total],$row[note]); 
					$c++;
				};		
			};	
		}else if($searchOper == 'gt'){
			if($res_search = $sqli->query("SELECT * FROM $table WHERE $searchField > $searchString")){
				while ($row=$res_search->fetch_array()){
					$responce->rows[$c][$searchField]=$row[$searchField];
					$responce->rows[$c]['cell']=array($row[id],$row[date],$row[amount],$row[tax],$row[total],$row[note]); 
					$c++;
				};		
			};	
		};
	};
}else {

	if($oper == 'edit'){
	$result = $sqli->query("UPDATE $table SET amount = $amount, tax = $tax, total = $total, `note` = '$note', `date` = '$date' 
			WHERE `id` = $id");	
	}else if($oper == 'add'){
		echo 'add';
		$result = $sqli->query("INSERT INTO $table (`id`, `date`, `amount`, `tax`, `total`, `note`)
			VALUES ($id, '$date', $amount, $tax, $total, '$note')");	
	}else if($oper == 'del'){ //нет такой опции
		$result = $sqli->query("DELETE FROM $table WHERE `id` = $id ");	
	}

	//Просмотр отредактированной таблицы
	$resAll = $sqli->query("SELECT * FROM $table");
	while($row = $resAll->fetch_array(MYSQL_BOTH)){
		$responce->rows[$i]['id']=$row[id];
		$responce->rows[$i]['cell']=array($row[id],$row[date],$row[amount],$row[tax],$row[total],$row[note]);
		$i++;	    
	}
}

$responce->records = $count;
$responce->page = $page;
$responce->total = $total_pages;

echo json_encode($responce);
