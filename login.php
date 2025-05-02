<?php	
	require "KULSO/config.php";
	require 'KULSO/functions.php';
	
	
	if (isset($_POST['login-btn'])) {

		$username = $_POST['email'];
		$jelszo = trim($_POST['password']);
		$email = $_POST['email'];
		
		$lekerdezes = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?"); 
		$lekerdezes->bind_param("ss", $username, $email);
		$lekerdezes->execute();
		$talalt_user = $lekerdezes->get_result();
		
		if ($talalt_user->num_rows > 0) {
			
			$user = $talalt_user->fetch_assoc();
			
			if ($user['confirmed'] == 0) {
				echo "<script>alert('Kérjük, erősítse meg a regisztrációját az e-mailben kapott linken keresztül!');</script>";
			} elseif (password_verify($jelszo, $user['password'])) {

				// Emlékezz rám checkbox ellenőrzése
				$remember = isset($_POST['remember']);
				$cookieTime = $remember ? (60 * 60 * 24 * 30) : 3600;

				setcookie("userid", $user['id'], time() + $cookieTime, "/");

				header("Location: index.php");
				exit();

			} else {
				echo "<script>alert('Hibás jelszó!')</script>";
			}

		} else {
			echo "<script>alert('Nem található felhasználó ilyen névvel!')</script>";
		}
	}


	
	if (isset($_POST['regist-btn'])){

		$username = $_POST['username'];
		$email = $_POST['email'];
		$jelszo = $_POST['pass1'];

		//Van -e már felh. ilyen névvel
		$lekerdezes = $conn->prepare("SELECT * FROM users WHERE username = ?");

		$lekerdezes->bind_param("s", $username);
		$lekerdezes->execute();
		$talalt_user = $lekerdezes->get_result();

		if ($talalt_user->num_rows == 0){
			
			//Foglalt -e az E-mail cím
			$lekerdezes = $conn->prepare("SELECT * FROM users WHERE email = ?");

			$lekerdezes->bind_param("s", $email);
			$lekerdezes->execute();
			$talalt_email = $lekerdezes->get_result();

			if ($talalt_email->num_rows == 0){
				
				if ($jelszo !== $_POST['pass2']){
					
					echo "<script>alert('A két jelszó nem egyezik!')</script>";
					
					exit();
				}

				if (strlen($jelszo) < 8) {
					
					echo "<script>alert('A jelszónak legalább 8 karakter hosszúnak kell lennie!')</script>";
					
					exit();
				}
				
				// Kis-nagybetű, szám és spec. karakter ellenőrzések
				if (!preg_match('/[A-Z]/', $jelszo)) {
					echo "<script>alert('A jelszónak tartalmaznia kell legalább egy nagybetűt!')</script>";
					exit();
				}

				if (!preg_match('/[a-z]/', $jelszo)) {
					echo "<script>alert('A jelszónak tartalmaznia kell legalább egy kisbetűt!')</script>";
					exit();
				}

				if (!preg_match('/[0-9]/', $jelszo)) {
					echo "<script>alert('A jelszónak tartalmaznia kell legalább egy számot!')</script>";
					exit();
				}

				if (!preg_match('/[\W]/', $jelszo)) {
					echo "<script>alert('A jelszónak tartalmaznia kell legalább egy speciális karaktert!')</script>";
					exit();
				}

				// Jelszótitkosítás
				$hashelt_jelszo = password_hash($jelszo, PASSWORD_DEFAULT);

                // Pontos létrehozás ideje
                $datum = date('Y-m-d H:i:s');

				//Adatok beszúrása
				$query = $conn->prepare("INSERT INTO users (username, email, password, created_at, aboutme, confirmed) VALUES (?, ?, ?, ?, ?, ?)");

				// Paraméterek hozzárendelése
				$aboutme = '';
				$confirmed = 0;
				$query->bind_param("sssssi", $username, $email, $hashelt_jelszo, $datum, $aboutme, $confirmed);

				//Ha sikeres volt a reg.
				if ($query->execute()){
					
					
					$lekerdezes = $conn->prepare("SELECT * FROM users WHERE email = ?");
					$lekerdezes->bind_param("s", $email);
					$lekerdezes->execute();
					$talalt_user = $lekerdezes->get_result();
					$user = $talalt_user->fetch_assoc();
					
					$code = generateUniqueCode($conn);
					$datum = date('Y-m-d H:i:s');
					$confirmLink = "https://team08.project.scholaeu.hu/confirm-regist.php?code=$code";

					// Mentés adatbázisba
					$stmt = $conn->prepare("INSERT INTO codes (userid, code, datum) VALUES (?, ?, ?)");
					$stmt->bind_param("iss", $user['id'], $code, $datum);
					$stmt->execute();
					
					//email küldés
					$body = '
					<p>Tisztelt ' . htmlspecialchars($username) . '!</p>

					<p>Köszönjük, hogy regisztrált a <strong>CherryT</strong> közösségbe. A regisztráció véglegesítéséhez kérjük, erősítse meg az e-mail címét az alábbi gombra kattintva:</p>

					<p style="margin: 20px 0;">
						<a href="'.$confirmLink.'" style="background-color:#6c5ce7; color:white; padding:10px 20px; border-radius:5px; text-decoration:none;">
							E-mail cím megerősítése
						</a>
					</p>

					<p>Amennyiben nem Ön kezdeményezte ezt a regisztrációt, kérjük, hagyja figyelmen kívül ezt az üzenetet. A fiók aktiválása csak a megerősítés után történik meg.</p>

					<p>További szép napot kívánunk!<br>
					Üdvözlettel:<br>
					<strong>A CherryT csapata</strong></p>
					';


					sendEmail(
						$email,
						$username,
						'CherryT - Regisztráció megerősítése',
						$body
					);

					echo "<script>alert('Küldtünk egy megerősítő emailt!')</script>";
					header("Location: login.php");
					
					exit();

				}
				else{
					die('Hiba az adatbázisba történő beszúrás során: ' . $query->error);
				}
				
			}
			else{
				echo "<script>alert('Ezzel az E-mail címmel már van regisztrált felhasználó!')</script>";
			}

		}
		else{
			echo "<script>alert('Ez a felhasználónév már foglalt!')</script>";
		}
	}
?>
<!DOCTYPE html>
<html lang="hu">
<head>
<meta charset="utf-8">
		<!-- NE TALALJAK MEG -->
		<meta name="robots" content="noindex, nofollow">
		
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="author" content="Greguricz Korinna, Bodnár Dorina">
		<meta name="description" content="CherryT">
		<meta name="keywords" content="Cserebere, Ingyen elvihető, Ajándék">
		<title>CherryT | Bejelentkezés</title>
		<!--  BOOTSRTAP -->
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
		
		<link rel="icon" href="images/favicon.ico" type="image/x-icon">
		
		<!-- CSS -->
		<link rel="stylesheet" type="text/css" href="css/login.css">
</head>
<body>   
    <div class="container-fluid d-flex justify-content-center align-items-center min-vh-100">
    <button id="indexgomb" type="button" class="btn btn-link fadeInSection">
    <a href="index.php"><i class="fa-solid fa-circle-arrow-left"></i></a>
	</button>

		<div class="row border rounded-5 p-3 bg-white shadow box-area fadeInSection">


    <!--------------------------- BAL OLDAL ----------------------------->

       <div class="col-md-6 rounded-4 d-flex justify-content-center align-items-center flex-column left-box" style="background: #840d46;">
           <div class="featured-image mb-3">
            <img src="images/1.png" class="img-fluid" style="width: 250px;">
           </div>
           <p class="text-white fs-2" style="font-family: 'Courier New', Courier, monospace; font-weight: 600;">CherryT</p>
           <small class="text-white text-wrap text-center" style="width: 17rem;font-family: 'Courier New', Courier, monospace;">Csereberélj te is velünk.</small>
       </div> 

    <!-------------------- ------ JOBB OLDAL ---------------------------->
        
       <div class="col-md-6 right-box">
          <div class="row align-items-center">
                <form id="regist-form" style="display: none;" method="post" action="login.php">
                <div class="header-text mb-4">
                     <h2>Regisztráció</h2>
                     <p>Legyél te is a közösségünk tagja.</p>
                </div>
                    <div class="input-group mb-2">
                        <input name="email" type="email" class="form-control form-control-lg bg-light fs-6" placeholder="Email cím" required>
                    </div>
                    <div class="input-group mb-2">
                        <input name="username" maxlength="16" type="text" class="form-control form-control-lg bg-light fs-6" placeholder="Felhasználónév" required>
                    </div>
                    <div class="input-group mb-2">
                        <input name="pass1" type="password" class="form-control form-control-lg bg-light fs-6" placeholder="Jelszó" required>
                    </div>
					<!-- Jelszó visszajelzés -->
						<div id="pass1-feedback" class="text-danger"></div><br>
                    <div class="input-group mb-2">
                        <input name="pass2" type="password" class="form-control form-control-lg bg-light fs-6" placeholder="Jelszó újra" required>
                    </div>
					<!-- Ismételt jelszó visszajelzés -->
						<div id="pass2-feedback"></div><br>
                    <div class="input-group mb-3 d-flex justify-content-between">
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="adatvCheck">
                            <label for="adatvCheck" class="form-check-label text-secondary"><small style="font-size: 12px;">Elolvastam és elfogadom az <a href="privacy-policy.php" target="_blank">Adatvédelmi nyilatkozatot.*</a></small></label>
                        </div>
                        <div class="form-check">
                            <input type="checkbox" class="form-check-input" id="felhCheck">
                            <label for="felhCheck" class="form-check-label text-secondary"><small style="font-size: 12px;">Elolvastam és elfogadom az <a href="policies.php" target="_blank">Felhasználási feltételeket.*</a></small></label>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input name="regist-btn" type="submit" class="btn btn-lg btn-lila w-100 fs-6" value="Regisztráció">
                    </div>
                    <div class="input-group mb-3">
                        <!-- warning -->
                    </div>
                    <div class="row">
                        <small>Van már fiókod? <a href="javascript:void(0);" onclick="toggleForm('login');">Jelentkezz be!</a></small>
                    </div>
                </form>
                <form id="login-form" method="post" action="login.php">
                <div class="header-text mb-4">
                     <h2>Bejelentkezés</h2>
                     <p>Örülünk, hogy újra itt vagy.</p>
                </div>
                    <div class="input-group mb-3">
                        <input name="email" type="text" class="form-control form-control-lg bg-light fs-6" placeholder="Email cím vagy felhasználónév"  required>
                    </div>
                    <div class="input-group mb-1">
                        <input name="password" type="password" class="form-control form-control-lg bg-light fs-6" placeholder="Jelszó" required>
                    </div>
                    <div class="input-group mb-5 d-flex justify-content-between">
                        <div class="form-check">
                            <input type="checkbox" name="remember" class="form-check-input" id="formCheck">
                            <label for="formCheck" class="form-check-label text-secondary"><small>Emlékezz rám.</small></label>
                        </div>
                        <div class="forgot">
                            <small><a href="forgot-pass.php">Elfelejtetted a jelszavad?</a></small>
                        </div>
                    </div>
                    <div class="input-group mb-3">
                        <input name="login-btn" type="submit" class="btn btn-lg btn-lila w-100 fs-6" value="Bejelentkezés">
                    </div>
                    <div class="input-group mb-3">
                         <!-- warning -->
                    </div>
                    <div class="row">
                        <small>Még nincs fiókod? <a href="javascript:void(0);" onclick="toggleForm('regist');">Regisztrálj!</a></small>
                    </div>
                </form>
          </div>
       </div> 

      </div>
    </div>
    <script src="https://kit.fontawesome.com/3970e10885.js" crossorigin="anonymous"></script>
</body>
</html>
<script>
    // A két form váltása
    function toggleForm(formType) {
        if (formType === 'regist') {
            document.getElementById('login-form').style.display = 'none';
            document.getElementById('regist-form').style.display = 'block';
        } else if (formType === 'login') {
            document.getElementById('login-form').style.display = 'block';
            document.getElementById('regist-form').style.display = 'none';
        }
    }

    //Az alapértelmezés a bejelentkezés legyen
    window.onload = function() {
        toggleForm('login');
    }
</script>

<script>
    var pass1 = document.getElementsByName('pass1')[0];
    var pass2 = document.getElementsByName('pass2')[0];
    var pass1Feedback = document.getElementById('pass1-feedback');
    var pass2Feedback = document.getElementById('pass2-feedback');

    // Valós idejű jelszóellenőrzés
    pass1.addEventListener('input', function() {
        validatePassword(pass1.value);
    });

    pass2.addEventListener('input', function() {
        checkPasswordsMatch(pass1.value, pass2.value);
    });

    // Ellenőrzi, hogy a jelszó megfelel-e minden kritériumnak
    function validatePassword(password) {
        var isValid = true;
        var pass1Conditions = [
            { 
                condition: password.length >= 8, 
                message: 'A jelszónak legalább 8 karakter hosszúnak kell lennie!' 
            },
            { 
                condition: /[a-z]/.test(password), 
                message: 'A jelszónak tartalmaznia kell legalább egy kisbetűt!' 
            },
            { 
                condition: /[A-Z]/.test(password), 
                message: 'A jelszónak tartalmaznia kell legalább egy nagybetűt!' 
            },
            { 
                condition: /[0-9]/.test(password), 
                message: 'A jelszónak tartalmaznia kell legalább egy számot!' 
            },
            { 
                condition: /[\W_]/.test(password), 
                message: 'A jelszónak tartalmaznia kell legalább egy speciális karaktert!' 
            }
        ];

        //Reset feedback text
        pass1Feedback.innerHTML = '';

        pass1Conditions.forEach(function(condition) {
            var conditionElement = document.createElement('div');
            conditionElement.textContent = condition.message;

            if (condition.condition) {
                conditionElement.style.color = 'green'; // Ha teljesül a feltétel
            } else {
                conditionElement.style.color = 'red'; // Ha nem teljesül a feltétel
            }

            pass1Feedback.appendChild(conditionElement);
        });
    }

	// Egyeznek -e
	function checkPasswordsMatch() {
		const pass1Value = pass1.value;
		const pass2Value = pass2.value;

		if (pass1Value === "" || pass2Value === "") {
			pass2Feedback.innerHTML = '';
			checkFormValidity();
			return;
		}

		if (pass1Value !== pass2Value) {
			pass2Feedback.innerHTML = 'A két jelszó nem egyezik!';
			pass2Feedback.style.color = 'red';
		} else {
			pass2Feedback.innerHTML = 'A két jelszó megegyezik.';
			pass2Feedback.style.color = 'green';
		}

		checkFormValidity(); // Frissítsd a regisztrációs gomb állapotát
	}
	pass1.addEventListener('input', function() {
    validatePassword(pass1.value);
    checkPasswordsMatch(); // ez fontos!
	});

	pass2.addEventListener('input', function() {
		checkPasswordsMatch();
	});



	
	
	const registForm = document.getElementById('regist-form');
    const registButton = registForm.querySelector('[name="regist-btn"]');
    const adatvCheck = document.getElementById('adatvCheck');
    const felhCheck = document.getElementById('felhCheck');
    const emailInput = registForm.querySelector('[name="email"]');
    const usernameInput = registForm.querySelector('[name="username"]');

    function isPasswordValid(password) {
        return (
            password.length >= 8 &&
            /[a-z]/.test(password) &&
            /[A-Z]/.test(password) &&
            /[0-9]/.test(password) &&
            /[\W_]/.test(password)
        );
    }

    function checkFormValidity() {
        const pass1Val = pass1.value;
        const pass2Val = pass2.value;

        const allFieldsFilled = emailInput.value && usernameInput.value && pass1Val && pass2Val;
        const passwordsMatch = pass1Val === pass2Val;
        const passwordValid = isPasswordValid(pass1Val);
        const checkboxesChecked = adatvCheck.checked && felhCheck.checked;

        registButton.disabled = !(allFieldsFilled && passwordsMatch && passwordValid && checkboxesChecked);
    }

    // változások figyelése
    [emailInput, usernameInput, pass1, pass2, adatvCheck, felhCheck].forEach(elem => {
        elem.addEventListener('input', checkFormValidity);
        elem.addEventListener('change', checkFormValidity);
    });

    window.addEventListener('load', () => {
        registButton.disabled = true;
    });
</script>

