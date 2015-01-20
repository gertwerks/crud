<?php

require 'Slim/Slim.php';

$app = new Slim();

$app->get('/users', 'getUsers');
$app->post('/users', 'addUser');

$app->get('/users/:id',	'getUser');
$app->get('/users/search/:query', 'findByName');
$app->put('/users/:id', 'updateUser');
$app->delete('/users/:id', 'deleteUser');

$app->get('/products', 'getProducts');
$app->post('/products', 'addProduct');

$app->run();

function getConnection() {
	$dbhost="localhost";
	$dbuser="root";
	$dbpass="root";
	$dbname="crud";
	$dbh = new PDO("mysql:host=$dbhost;dbname=$dbname", $dbuser, $dbpass);	
	$dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	return $dbh;
}

function getUsers() {
	$sql = "select * FROM users ORDER BY id";
	try {
		$db = getConnection();
		$stmt = $db->query($sql);  
		$users = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($users);
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function getProducts() {
	$sql = "select * FROM products ORDER BY id";
	try {
		$db = getConnection();
		$stmt = $db->query($sql);  
		$products = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($products);
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function getUser($id) {
	$sql = "SELECT * FROM users WHERE id=:id";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("id", $id);
		$stmt->execute();
		$user = $stmt->fetchObject();  
		$db = null;
		echo json_encode($user); 
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function addUser() {
	$request = Slim::getInstance()->request();
	$user = json_decode($request->getBody());
	$sql = "INSERT INTO users (firstname, lastname, email, grades, img) VALUES (:firstname, :lastname, :email, :grades, :img)";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("firstname", $user->firstname);
		$stmt->bindParam("lastname", $user->lastname);
		$stmt->bindParam("email", $user->email);
		$stmt->bindParam("grades", $user->grades);
		$stmt->bindParam("img", $user->img);
		$stmt->execute();
		$user->id = $db->lastInsertId();
		$db = null;
		echo json_encode($user); 
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function addProduct() {
	$request = Slim::getInstance()->request();
	$products = json_decode($request->getBody());
	$sql = "INSERT INTO products (img, productname, productdetails) VALUES (:img, :productname, :productdetails)";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("img", $products->img);
		$stmt->bindParam("productname", $products->productname);
		$stmt->bindParam("productdetails", $products->productdetails);
		$stmt->execute();
		$products->id = $db->lastInsertId();
		$db = null;
		echo json_encode($products); 
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function updateUser($id) {
	$request = Slim::getInstance()->request();
	$body = $request->getBody();
	$user = json_decode($body);
	$sql = "UPDATE users SET img=:img, firstname=:firstname, lastname=:lastname, email=:email, grades=:grades WHERE id=:id";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("img", $user->img);
		$stmt->bindParam("firstname", $user->firstname);
		$stmt->bindParam("lastname", $user->lastname);
		$stmt->bindParam("email", $user->email);
		$stmt->bindParam("grades", $user->grades);
		$stmt->bindParam("id", $id);
		$stmt->execute();
		$db = null;
		echo json_encode($user); 
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function deleteUser($id) {
	$sql = "DELETE FROM users WHERE id=:id";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);  
		$stmt->bindParam("id", $id);
		$stmt->execute();
		$db = null;
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

function findByName($query) {
	$sql = "SELECT * FROM users WHERE UPPER(firstname) LIKE :query ORDER BY firstname";
	try {
		$db = getConnection();
		$stmt = $db->prepare($sql);
		$query = "%".$query."%";  
		$stmt->bindParam("query", $query);
		$stmt->execute();
		$users = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo '{"user": ' . json_encode($users) . '}';
	} catch(PDOException $e) {
		echo '{"error":{"text":'. $e->getMessage() .'}}'; 
	}
}

?>