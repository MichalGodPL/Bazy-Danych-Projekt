<?php

$servername = "localhost";

$username = "root";

$password = "";

$dbname = "bazydanychprojekt";


// Utworzenie Połączenia

$conn = new mysqli($servername, $username, $password, $dbname);


// Sprawdzenie Połączenia

if ($conn->connect_error) {

    die("Connection failed: " . $conn->connect_error);
}


function prettifyTableName($tableName) {

    $prettifiedNames = [

        "tabeladyrektorzy" => "Tabela Dyrektorów",

        "tabelaklienci" => "Tabela Klientów",

        "tabelapracownicy" => "Tabela Pracowników",

        "tabelazaplanowanezlecenia" => "Tabela Zaplanowanych Zleceń",

        "tabelaszkolenpracownikow" => "Tabela Szkoleń Pracowników",

        "tabelazaplanowanychusprawnien" => "Tabela Zaplanowanych Zakupów",

        "tabelazasobowmarketingowych" => "Tabela Zasobów Marketingowych",

        "tabelarozliczenfinansowych" => "Tabela Rozliczeń Finansowych"

    ];
    
    return $prettifiedNames[$tableName] ?? ucwords(str_replace('_', ' ', $tableName));

}


function getColumnNames($tableName) {

    $columnNames = [

        "tabeladyrektorzy" => ["Board ID", "Imię i Nazwisko", "Miesięczne Zarobki", "Data Zatrudnienia"],

        "tabelaklienci" => ["Numer Porządkowy", "Nazwa Firmy", "NIP", "KraJ Pochodzenia", "Wykonana Usługa", "Dyrektor - Koordynator", "Wielkość Firmy", "Mail Kontakowy","Cena Usługi", "Data Rozpoczęcia", "Liczba Dni"],

        "tabelapracownicy" => ["Worker ID", "Imię i Nazwisko", "Stanowisko", "Data Zatrudnienia", "Miesięczne Zarobki", "Dział"],

        "tabelazaplanowanezlecenia" => ["Numer Porządkowy","Data Rozpoczęcia", "Firma Klient", "Zlecenie", "Cena Zlecenia", "Przewidywany Okres Wykonywania", "Dyrektor - Koordynator", "Kraj Pochodzenia", "Liczba Przypisanych Pracowników"],

        "tabelaszkolenpracownikow" => ["Numer Porządkowy", "Przydzielony Pracownik (By Worker ID)", "Dział", "Stanowisko", "Tematyka Szkolenia", "Koszt Szkolenia", "Data Szkolenia", "Lokalizacja", "Ulica"],

        "tabelazaplanowanychusprawnien" => ["Numer Porządkowy", "Zakup - Przedmiot", "Przewidywana Data Zakupu", "Koszt Jednostkowy", "Koszt Całościowy", "Liczba", "Okres Korzystania"],

        "tabelazasobowmarketingowych" => ["Numer Porządkowy", "Nazwa Zasobu", "Data Utworzenia", "Typ Zasobu", "Kanał Dystrybucji", "Status"],

        "tabelarozliczenfinansowych" => ["Numer Porządkowy", "Miesiąc", "Rok", "Przychody", "Koszty", "Dochód"]

    ];

    return $columnNames[$tableName] ?? [];

}

$tablesResult = $conn->query("SHOW TABLES");

$tableNames = [];

if ($tablesResult->num_rows > 0) {

    while ($row = $tablesResult->fetch_array()) {

        $tableNames[] = $row[0];

    }

} else {

    $tableNames[] = null;

}


$currentTableIndex = 0;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $currentTableIndex = (int)$_POST['currentTableIndex'];

    if (isset($_POST['previous'])) {

        $currentTableIndex = ($currentTableIndex - 1 + count($tableNames)) % count($tableNames);

    } elseif (isset($_POST['next'])) {

        $currentTableIndex = ($currentTableIndex + 1) % count($tableNames);

    }

}

$currentTable = $tableNames[$currentTableIndex] ?? null;

?>


<!DOCTYPE html>

<html>

<head>


    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">

    <style>

        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap');
        
        body {

            font-family: 'Poppins', sans-serif;
            
            background-color: #f9f9f9;

            margin: 0;

            padding: 0;

        }

        .container {

            margin: 50px auto;

            width: 80%;

            padding: 20px;

            background-color: #ffffff;

            box-shadow: 0 2px 5px rgba(0,0,0,0.1);

            border-radius: 10px;

        }

        .button {

            background-color: #f6bd60;

            color: white;

            padding: 14px 20px;

            margin: 8px 0;

            border: none;

            cursor: pointer;

            width: calc(100% - 16px);

            text-align: center;

            transition: transform 0.2s, background-color 0.2s;

            border-radius: 30px;

            font-size: 18px;

            font-weight: 500;

            display: block;

            margin-left: auto;

            margin-right: auto;

            text-transform: uppercase;

            text-decoration: none;

            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);

        }

        .button:hover {

            transform: scale(1.05);

            background-color: #e0a54d;

        }

        table {

            width: 100%;

            border-collapse: collapse;

            border: none;

            margin-top: 20px;

            opacity: 0;

            animation: fadeIn 0.5s forwards;

            border: 1px solid #dddddd;

            border-radius: 5px;

            overflow: hidden;

            box-shadow: 0 1px 2px rgba(0,0,0,0.1);

            transition: opacity 0.5s ease-out;

        }

        @keyframes fadeIn {

            from {

                opacity: 0;

                transform: translateY(20px);

            }

            to {

                opacity: 1;

                transform: translateY(0);

            }
        }

        table, th, td {

            border: 1px solid #dddddd;

        }

        th, td {

            padding: 12px;

            text-align: left;

            transition: transform 0.2s, color 0.2s;

        }

        td:hover {

            transform: scale(1.05);

            color: #ffafcc;

        }

        th {

            background-color: #ffafcc;

            color: white;

            font-weight: 600;

            border: none;

        }

        th:hover {

            transform: none;

        }

        td {

            background-color: #f9f9f9;

            border-right: none;

            border-top: none;

            border-left: none;

            border-bottom: 1.5px solid #dddddd;

        }

        h1, h2 {

            font-weight: 700;

            color: #333333;

            text-align: center;

        }

        .navigation {

            display: flex;

            justify-content: center;

            align-items: center;

            margin-top: 20px;

        }

        .nav-button {

            background: none;

            border: none;

            cursor: pointer;

            font-size: 36px;

            font-weight: 700;

            color: #ffafcc;

            margin: 0 30px;

            transition: transform 0.2s, color 0.2s;

        }

        .nav-button:hover {

            transform: scale(1.2);

            color: #E59DB7;

        }

        .table-name {

            font-size: 36px;

            font-weight: 700;

            color: #333;

        }

    </style>


    <script>

        function transitionTables() {

            const table = document.querySelector('table');

            table.style.opacity = 0;

            setTimeout(() => {

                table.style.opacity = 1;

            }, 500);

        }

    </script>


</head>

<body>

<div class="container">

    <h1>Baza Danych - Michałex Polska</h1>

    <div class="navigation">

        <form method="POST" style="display: inline;" onsubmit="transitionTables()">

            <input type="hidden" name="currentTableIndex" value="<?php echo $currentTableIndex; ?>">

            <button type="submit" name="previous" class="nav-button"><i class="fas fa-arrow-left"></i></button>

        </form>


        <span class="table-name"><?php echo prettifyTableName($currentTable); ?></span>

        <form method="POST" style="display: inline;" onsubmit="transitionTables()">

            <input type="hidden" name="currentTableIndex" value="<?php echo $currentTableIndex; ?>">

            <button type="submit" name="next" class="nav-button"><i class="fas fa-arrow-right"></i></button>

        </form>

    </div>


    <?php

    if ($currentTable) {

        $result = $conn->query("SELECT * FROM $currentTable");


        if ($result->num_rows > 0) {

            echo "<table><tr>";

            $columnNames = getColumnNames($currentTable);

            foreach ($columnNames as $columnName) {

                echo "<th>" . htmlspecialchars($columnName) . "</th>";

            }

            echo "</tr>";

            while ($row = $result->fetch_assoc()) {

                echo "<tr>";

                foreach ($row as $value) {

                    $columns = explode(';', $value);

                    foreach ($columns as $column) {

                        echo "<td>" . htmlspecialchars($column) . "</td>";

                    }

                }

                echo "</tr>";

            }

            echo "</table>";

        } else {

            echo "<p>Brak Danych.</p>";

        }

    } else {

        echo "<p>Brak Tabel.</p>";

    }

    $conn->close();

    ?>

</div>

</body>

</html>