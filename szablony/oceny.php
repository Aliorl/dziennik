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
    <?php if (isset($_SESSION['username'])) {?>
    <body class="sb-nav-fixed">
        <nav class="sb-topnav navbar navbar-expand navbar-dark bg-dark">
            <!-- Navbar Brand-->
            <a class="navbar-brand ps-3" href="strona_glowna.php"><img src="gfx/005-planner.svg" style="width:20%;"> Dziennik elektroniczny</a>
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
                        <h1 class="mt-4">Lista ocen</h1>
                        <div class="card-header"><img src="gfx/015-tick.svg" style="width:4%;"><b>Dodawanie ocen i edycja danych:</b>
                        <p class="mb-0">
                                <?php if (isset($_SESSION['username'])){?>
                                <form action="oceny.php" method="post">
                                	<input type='hidden' name="id" value="<?=$ID?>">
                                	<br>Wybierz zajęcia: <select id="id_zajec" name="id_zajec" title="Zajęcia">
								<?php 
								foreach ($ZAJECIA as $zajecia){
									$selected=($ID_ZAJEC==$zajecia["id_zajec"])?" selected ":"";
									echo '<option value="'.$zajecia["id_zajec"].'"'.$selected.'>'.$zajecia["nazwa_zajec"].'</option>'.PHP_EOL;
								}?>
									</select>
                                	<br>Wybierz prowadzącego: <select id="id_prowadzacego" name="id_prowadzacego" title="Prowadzący">
								<?php
								foreach ($PROWADZACY as $prowadzacy){
								    $selected=($ID_PROWADZACEGO==$prowadzacy["id_prowadzacego"])?" selected ":"";
								    echo '<option value="'.$prowadzacy["id_prowadzacego"].'"'.$selected.'>'.$prowadzacy['stopien_naukowy']." ".$prowadzacy['imie']." ".$prowadzacy['nazwisko'].'</option>'.PHP_EOL;
								}?>
									</select>
									<br>Wybierz semestr: <select id="id_semestru" name="id_semestru" title="Semestr">
								<?php 
								foreach ($SEMESTRY as $semestr){
								    $selected=($ID_SEMESTRU==$semestr["id_semestru"])?" selected ":"";
								    echo '<option value="'.$semestr["id_semestru"].'"'.$selected.'>'.$semestr["numer_semestru"].'</option>'.PHP_EOL;
								}?>
									</select>
									<br>Wybierz studenta: <select id="id_studenta" name="id_studenta" title="Studenci">
								<?php 
								foreach ($STUDENCI as $student){
									$selected=($ID_STUDENTA==$student["id_studenta"])?" selected ":"";
									echo '<option value="'.$student["id_studenta"].'"'.$selected.'>'.$student["imieS"]." ".$student["nazwiskoS"].'</option>'.PHP_EOL;
								}?>
									</select>
									<br>Wybierz rodzaj oceny: <select id="id_rodzaju_ocen" name="id_rodzaju_ocen" title="Rodzaj oceny">
								<?php 
								foreach ($RODZAJE_OCEN as $rodzaj_oceny){
								    $selected=($RODZAJ_OCENY==$rodzaj_oceny["id_rodzaju_ocen"])?" selected ":"";
								    echo '<option value="'.$rodzaj_oceny["id_rodzaju_ocen"].'"'.$selected.'>'.$rodzaj_oceny["rodzaj_oceny"].'</option>'.PHP_EOL;
								}?>
									</select>
									<br>Wybierz ocenę: <select id="id_stopnia" name="id_stopnia" title="Ocena">
								<?php 
								foreach ($STOPNIE as $stopien){
								    $selected=($OCENA==$stopien["id_stopnia"])?" selected ":"";
								    echo '<option value="'.$stopien["id_stopnia"].'"'.$selected.'>'.$stopien["stopien"].'</option>'.PHP_EOL;
								}?>
									</select>
								<div class="card-body">
								<div class="w3-bar">
  									<button class="w3-bar-item w3-button w3-round w3-green" style="width:30%" type="submit" name="dodaj">Dodaj nową ocenę</button>
  									<button class="w3-bar-item w3-button w3-round w3-yellow" style="width:20%"type="submit" name="zmien">Zapisz zmiany</button>
								</div>
                            	</div>
								</form>
							</div>
							<?php } ?>
                        	<form action="oceny.php" method="post">
                        	<div class="card-header"> Filtruj po wybranym studencie: 
                        	<select id="student" name="student" title="Student">
                        	<?php
                        		foreach ($STUDENCI as $student){
									$selected=($ID_STUDENTA==$student["id_studenta"])?" selected ":"";
									echo '<option value="'.$student["id_studenta"].'"'.$selected.'>'.$student["imieS"]." ".$student["nazwiskoS"].'</option>'.PHP_EOL;
							}?>
							</select>
							<button class="w3-bar-item w3-button w3-round w3-dark-grey" style="width:20%"type="submit" name="wyświetl">Wyświetl listę</button>
                        </div>
                        <div class="card-header"><strong><?php if (isset($_POST['student'])) echo '<i class="fas fa-columns"></i> Lista ocen studenta (nr albumu): '.$_POST['student']?></strong></div>
						</form>
                        <div class="card mb-4">
                            <div class="card-body">
                                <p class="mb-0">
                                <div id="oceny_list">
                                <form action="oceny.php" method="post">
                                <?php
                                echo '<div class="w3-responsive"><table class="w3-table-all w3-card-4">';
                                if (isset($_SESSION['username'])) $dodatkowa_kolumna='<th> </th>'; else $dodatkowa_kolumna="";
                                echo '<tr><th>Zajęcia</th><th>Prowadzący</th><th>Semestr</th><th>Student</th><th>Rodzaj oceny</th><th>Ocena</th>'.$dodatkowa_kolumna.'</tr>'.PHP_EOL;
                                foreach ($OCENY as $ocena){
                                    echo "<tr>";
                                    echo "<td>".$ocena["nazwa_zajec"]."</td>
                                    <td>".$ocena["stopien_naukowy"]." ".$ocena["imie"]." ".$ocena["nazwisko"]."</td>
                                    <td>".$ocena["id_semestru"]."</td>
                                    <td>".$ocena["imieS"]." ".$ocena["nazwiskoS"]."</td>
                                    <td>".$ocena["rodzaj_oceny"]."</td><td>".$ocena["id_stopnia"]."</td>";
                                    if (isset($_SESSION['username'])){
                                        echo "<td>";
                                        echo "<a class='btn btn-primary btn-block' title='Edytuj' href='oceny.php?op=0&id=".$ocena['id_oceny']."'>Edytuj</a>";
                                        echo "</td>";
                                        echo "<td>";
                                        echo "<a class='w3-button w3-red' title='Usuń' href='oceny.php?op=1&id=".$ocena['id_oceny']."'>Usuń</a>";
                                        echo "</td>";
                                    }
                                    echo "</tr>".PHP_EOL;
                                }
                                echo "</table></div>";?>
								</form>
								</div>
                                </p>
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
    </body>
    	<?php }
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