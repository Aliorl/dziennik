<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8" />
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
<meta name="description" content="" />
<meta name="author" content="" />
<title>Logowanie</title>
<link href="css/styles.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>
</head>
<div id="layoutAuthentication">
<div id="layoutAuthentication_content">
<main>
<div class="container">
<div class="row justify-content-center">
<div class="col-lg-5">
<div class="card shadow-lg border-0 rounded-lg mt-5">
<div class="card-header"><h3 class="text-center font-weight-light my-4"><img src="gfx/005-planner.svg" style="width:14%;"> E-Dziennik </h3></div>
<div class="card-body">
<form action="logowanie.php" method="post">
<?=$KOMUNIKAT?>
<?php if (isset($_SESSION['username'])) 
    echo '<p><a href="strona_glowna.php">Przejdź do strony głównej</a></p>
        <form action="logowanie.php" method="post">
               <div class="d-grid"><input class="btn btn-primary btn-block" type="submit" name="submit" value="Wyloguj"></div>
 		</form>'; else {?>
<div class="form-floating mb-3">
<input class="form-control" id="inputLogin" type='text' name="login" title="Podaj swój login" value="<?=$LOGIN?>"/>
<label for="inputLogin">Login / Adres E-Mail</label>
</div>
<div class="form-floating mb-3">
<input class="form-control" id="inputPassword" type='password' name="password" title="Wprowadź hasło" value="" />
<label for="inputPassword">Hasło</label>
</div>
<!-- <div class="form-check mb-3"> -->
<!-- <input class="form-check-input" id="inputRememberPassword" type="checkbox" value="" /> -->
<!-- <label class="form-check-label" for="inputRememberPassword">Zapamiętaj hasło</label> -->
<!-- </div> -->
<div class="d-flex align-items-center justify-content-between mt-4 mb-0">
<!-- <a class="small" href="haslo.php">Zmiana hasła</a> -->
<div class="d-grid"><input class="btn btn-primary btn-block" type="submit" name="submit" value="Zaloguj"></div>
</div>
</form>
</div>
<div class="card-footer text-center py-3">
<!-- <div class="small"><a href="rejestracja.php">Potrzebujesz konta? Zarejestruj się!</a></div> -->
<?php } ?>
</div>
</div>
</div>
</div>
</div>
</main>
</div>
<div id="layoutAuthentication_footer">
<footer class="py-4 bg-light mt-auto">
                    <div class="container-fluid px-4">
                        <div class="d-flex align-items-center justify-content-between small">
                            <div class="text-muted">Copyright &copy; Icons made by <a href="https://www.freepik.com">Freepik</a>
                            </div>
                            <div>
                                <a href="#">Polityka prywatności</a>
                            </div>
                        </div>
                    </div>
                </footer>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
<script src="js/scripts.js"></script>
</body>
</html>