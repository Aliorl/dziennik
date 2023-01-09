<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
        <meta name="description" content="" />
        <meta name="author" content="" />
        <title>Strona główna</title>
        <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
		<link rel="stylesheet" href="https://www.w3schools.com/lib/w3-theme-teal.css">
		<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto">
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
        <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
        <link href="css/styles.css" rel="stylesheet" />
        <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js" crossorigin="anonymous"></script>
    </head>
    <?php if (isset($_SESSION['username'])) {
    ?>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <!-- Navbar Brand-->
            <a class="navbar-brand ps-3" href="strona_glowna.php"><img src="gfx/005-planner.svg" style="width:20%;"> E-Dziennik </a>
            <!-- Navbar Search-->
            <form class="d-none d-md-inline-block form-inline ms-auto me-0 me-md-3 my-2 my-md-0">
                <div class="input-group">
                    <input class="form-control" type="text" placeholder="Szukaj..." aria-label="Szukaj..." aria-describedby="btnNavbarSearch" />
                    <button class="btn btn-primary" id="btnNavbarSearch" type="button"><i class="fas fa-search"></i></button>
                </div>
            </form>
            <!-- Navbar-->
            <a class="sb-nav-link-icon"><i class="fas fa-user fa-fw"></i></a>
            		<ul class="navbar-nav ms-auto ms-md-0 me-3 me-lg-4">
                        <li><a class="dropdown-item"> 
                        		<form action="logowanie.php" method="post">
 									<div class="d-grid"><input class="btn btn-primary btn-block" type="submit" name="submit" value="Wyloguj"></div>
 								</form>
 							</a>
 						</li>
                    </ul>
                </li>
            </ul>
        </nav>
        <div id="layoutSidenav">
            <div id="layoutSidenav_nav">
                <nav class="sb-sidenav accordion sb-sidenav-dark" id="sidenavAccordion">
                    <div class="sb-sidenav-menu">
                        <div class="nav">
                            <div class="sb-sidenav-menu-heading">Menu</div>
                            <a class="nav-link" href="strona_glowna.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-tachometer-alt"></i></div>
                                Strona główna
                            </a>
                            <div class="sb-sidenav-menu-heading">Zajęcia</div>
                            <a class="nav-link" href="zajecia_studentow.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                Lista zajęć
                            </a>
                           <a class="nav-link" href="lista_studentow.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                                Lista studentów
                            </a>
                            <div class="sb-sidenav-menu-heading">Panel użytkownika</div>
                            <a class="nav-link" href="oceny.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-chart-area"></i></div>
                                Oceny
                            </a>
                            <a class="nav-link" href="frekwencja.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-table"></i></div>
                                Frekwencja
                            </a>
                            <a class="nav-link" href="tematy.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-book-open"></i></div>
                                Tematy
                            </a>
                            <a class="nav-link" href="uwagi.php">
                                <div class="sb-nav-link-icon"><i class="fas fa-columns"></i></div>
                                Uwagi
                            </a>
                        </div>
                    </div>
                   		<div class="sb-sidenav-footer">
                        <div class="small">  
                        <?php if (isset($_SESSION['username'])) echo "Jesteś zalogowany jako ".$_SESSION['username']?>
                        </div>
                    </div>
                </nav>
            </div>
            <div id="layoutSidenav_content">
                <main>
                    <div class="container-fluid px-4">
                        <h1 class="mt-4">Frekwencja</h1>
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="card mb-4">
                                    <div class="card-header">
                                        <i class="fas fa-chart-bar me-1"></i>
                                        Wykres frekwencji
                                    </div>
                                    <div class="card-body"><canvas id="myBarChart" width="100%" height="40"></canvas></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </main>
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
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.8.0/Chart.min.js" crossorigin="anonymous"></script>
        <script src="js/srednia_ocen.js"></script>
        <script src="js/frekwencja.js"></script>
        <script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
    </body>
    <?php 
    }
    else { ?>
                         <div class="container">
                         <div class="row justify-content-center">
                         <div class="col-lg-5">
                         <div class="card shadow-lg border-0 rounded-lg mt-5">
                         <div class="card-header"><h3 class="text-center font-weight-light my-4"><img src="gfx/005-planner.svg" style="width:14%;"> Logowanie</h3></div>
                         <div class="card-body">
                         <form action="logowanie.php" method="post">
                         Żeby mieć dostęp do dziennika, musisz być zalogowany
                         <div class="form-floating mb-3">
                         <input class="form-control" id="inputEmail" type='text' name="login" title="Podaj swój login" value=""/>
                         <label for="inputLogin">Login / Adres E-Mail</label>
                         </div>
                         <div class="form-floating mb-3">
                         <input class="form-control" id="inputPassword" type='password' name="password" title="Wprowadź hasło" value="" />
                         <label for="inputPassword">Hasło</label>
                         </div>
                         <div class="form-check mb-3">
                         <input class="form-check-input" id="inputRememberPassword" type="checkbox" value="" />
                         <label class="form-check-label" for="inputRememberPassword">Zapamiętaj hasło</label>
                         </div>
                         <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                         <a class="small" href="haslo.php">Zmiana hasła</a>
                         <div class="d-grid"><input class="btn btn-primary btn-block" type="submit" name="submit" value="Zaloguj"></div>
                         </div>
                         </form>
                         </div>
                         <div class="card-footer text-center py-3">
                         <div class="small"><a href="rejestracja.php">Potrzebujesz konta? Zarejestruj się!</a></div>
                         </div>
                         </div>
                         </div>
                         </div>
                         </div>
                     <?php };?>
</html>