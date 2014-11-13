<?php
function head() {
	header("{$_SERVER['SERVER_PROTOCOL']} 200 OK");
    header('Content-Type: application/json'); // Odgovore na zahtjev vracamo u JSON formatu
    header('Access-Control-Allow-Origin: *'); // Podrska za CORS
}

// Pomocne funkcije --- pozivaju se iz metoda post, get, put i delete zavisno od URIa
function databaseConnection() {
	$bind = new PDO('mysql:dbname=simpledb;host=localhost', 'root', '');
	$bind->exec("set names utf8");
	return $bind;
}
function createUser($request) {
	$bind=databaseConnection();

	$username = $request->params['username'];
	$password = md5( $request->params['password']);
	$email =  $request->params['email'];
	$photo =  $request->params['photo'];

	$query = $bind->prepare('insert into korisnik (korisnicko_ime, sifra, email, slika) values(:u, :p, :e, :s)');
	$query->bindParam(':u', $username);
	$query->bindParam(':p', $password);
	$query->bindParam(':e', $email);
	$query->bindParam(':s', $photo);
	$query->execute();
}
function login($request) {
	session_start();

	$bind=databaseConnection();

	$username = $request->params['username'];
	$password = md5( $request->params['password']);

	$query = $bind->prepare('select korisnicko_ime from korisnik where korisnicko_ime = :user and sifra = :pass');
    $query->bindParam(':user', $username);
    $query->bindParam(':pass', $password);
    $query->execute();
    
    if($query->rowCount() == 1) {
        $_SESSION['username'] = $username; // Postavljamo username u sesiji
        
    }
    else {
        echo "{ \"poruka\": \"greska\" }";
    }

}

function logout($request) {
	 session_unset();
}

function addPortal($request) {
	session_start();

	if(isset($_SESSION['username'])) {
		$bind=databaseConnection();

		$username=$_SESSION['username'];
		$url=$request->params['url'];
		$query=$bind->prepare('insert into portal (korisnik, url) values (:k, :u)');
		$query->bindParam(':k', $username);
		$query->bindParam(':u', $url);
		$query->execute();
	}
}

function getPortals($request) {
	session_start();

	if(isset($_SESSION['username'])) {
		$bind=databaseConnection();

		$username=$_SESSION['username'];
		$query = $bind->query("select * from portal where korisnik = '".$username."'");
		
		echo json_encode($query->fetchAll());
	}

}

function deletePortal($request) {
	session_start();

	if(isset($_SESSION['username'])) {
		$bind=databaseConnection();
		$username=$_SESSION['username'];
		$id=$request->uri[sizeof($request->uri)-1];

		$query = $bind->prepare("delete from portal where portal_id = :id");
		$query->bindParam(':id', $id);
    	$query->execute();
    	
		
	}

}

function getUser() {
	session_start();

	if(isset($_SESSION['username'])) {
		$bind=databaseConnection();

		$username=$_SESSION['username'];
		$query2 = $bind->query("select * from korisnik where korisnicko_ime='".$username."'");
		$user=$query2->fetch(PDO::FETCH_ASSOC);
      
	    echo "{ \"username\": \"".$user['korisnicko_ime']."\","."\"email\": \"".$user['email']."\","."\"password\": \"".$user['sifra']."\","."\"photo\": \"".$user['slika']."\"}";
	}
}

function updateUser($request) {
	session_start();

	if(isset($_SESSION['username']) && isset($request->params['photo'])) {
		$bind=databaseConnection();
		$username=$_SESSION['username'];
		$photo=$request->params['photo'];
		$query = $bind->prepare('update korisnik set slika = :p where korisnicko_ime= :u');
		$query->bindParam(':p', $photo);
		$query->bindParam(':u', $username);
		$query->execute();

	}

}

// Funkcije u zavisnosti od HTTP metode

function get($request) { // Implementacija u slucaju GET metode
	if($request->uri[sizeof($request->uri)-1]=="login" && $request->params['unset']=="true") {
		logout($request);
	}
	else if($request->uri[sizeof($request->uri)-1]=="user") {
			getUser($request);
	}
	else if($request->uri[sizeof($request->uri)-1]=="portal") {
			getPortals($request);
	}
} 

function post($request) { // Implementacija u slucaju POST metode
		if($request->uri[sizeof($request->uri)-1]=="user") {
			createUser($request);
		}
		else if($request->uri[sizeof($request->uri)-1]=="login") {
			login($request);
		}
		else if ($request->uri[sizeof($request->uri)-1]=="portal"){
			addPortal($request);
		}
} 

function put($request) {
	if($request->uri[sizeof($request->uri)-1]=="user") {
			updateUser($request);
		}
		else if ($request->uri[sizeof($request->uri)-1]=="portal"){
			updatePortal($request);
		}
} // Implementacija u slucaju PUT metode
function delete($request) {

	deletePortal($request);
} // Implementacija u slucaju DELETE metode

class req {
	public $method=""; // Metoda koja se poziva
	public $params= array(); // Parametri metode
	public $uri= array(); // URI sa kojim je pozvana metoda

	public function process($reqMethod, $reqUri) {
		switch($reqMethod) {
			case 'GET':
				head(); // Prvo postavljamo zaglavlja
				$this->method=$reqMethod;
				$this->params=$_GET;
				$this->uri=explode("/",$reqUri);
				get($this);
				break;
			case 'PUT':
				head(); // Prvo postavljamo zaglavlja
				$this->method=$reqMethod;
				// Kako ne postoji globalna varijabla $_PUT koja bi ocitala parametre 
				// potrebno koristimo ulazni read-only stream koji ocitava podatke iz zahtjeva
				parse_str(file_get_contents('php://input'), $put_vars); 
				$this->params=$put_vars;
				$this->uri=explode("/",$reqUri);
				put($this);
				break;
			case 'POST':
				head(); // Prvo postavljamo zaglavlja
				$this->method=$reqMethod;
				$this->params=$_POST;
				$this->uri=explode("/", $reqUri);
				post($this);
				break;
			case "DELETE":
				head(); // Prvo postavljamo zaglavlja
				$this->method=$reqMethod;
				// Kako ne postoji globalna varijabla $_PUT koja bi ocitala parametre 
				// potrebno koristimo ulazni read-only stream koji ocitava podatke iz zahtjeva
				parse_str(file_get_contents('php://input'), $delete_vars); 
				$this->params=$delete_vars;
				$this->uri=explode("/",$reqUri);
				delete($this);
				break;
			default:
				header("{$_SERVER['SERVER_PROTOCOL']} 404 Not Found");
        		rest_error($request); break;
		}
	}
}
$newRequest = new req();
// Pomocu globalne varijable $_SERVER ocitavamo metodu i URI zahtjeva
$newRequest->process($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);


?>