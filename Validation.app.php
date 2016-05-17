<?php require 'App.php';
$app = new App;

echo '<pre>';
$customMessages = ['SERVER_PORT:REQUIRED' => 'You must enter this!', 'SERVER_PORT:ip' => 'IPV4 Only!']; 
$customNames = ['SERVER_PORT' => 'Serveeer port'];
#$a = Validation::on('ENV')->screen('SERVER_PORT', 'required', 'ip:ipv4', 'between:5,100', 'in:1,2,3,4,5,6,81');
#$b = Validation::on('ENV')->screen('REMOTE_ADDR', 'required', 'ip:ipv4');
#$c = Validation::on('ENV')->screen('SERVER_PORT|required|ip:ipv4|between:5,100|in:1,2,3,4,5,6,80,81', 'REMOTE_ADDR|required|ip:ipv4');
#$d = Validation::on('ENV')->screen(['SERVER_PORT' => 'required|ip:ipv4|between:5,100|in:1,2,3,4,5,6,81', 'REMOTE_ADDR' => 'required|ip:ipv4']);
#$e = Validation::on('ENV')->screen(['SERVER_PORT' => ['required', ['ip', 'ipv4'], ['between', 5, 100], ['in', 1, 2, 3, 4, 5, 6, 81]], 'REMOTE_ADDR' => ['required', ['ip', 'ipv4']]]);
#$f = Validation::on('ENV')->screen(['SERVER_PORT' => ['required', 'ip:ipv4', 'between:5,100', 'in:1,2,3,4,5,6,81'], 'REMOTE_ADDR' => ['required', 'ip:ipv4']]);
#$g = Validation::on('ENV')->screen(['SERVER_PORT' => ['required', 'ip' => 'ipv4', 'between' => [5, 100], 'in' => [1,2,3,4,5,6,81]], 'REMOTE_ADDR' => ['required', 'ip' => 'ipv4']]);


$instructions = [ 
	'SERVER_PORT' =>'required|ip:ipv4|between:5,70|in:1,2,3,4,5,6,82,81',
	'REMOTE_ADDR' => 'required|ip:ipv4'
];

$a = $_ENV;

$book = Validation::on($a, $customMessages)
	->attributes($customNames)
	->screen($instructions)
	->execute()
	->book();
$book->all();

