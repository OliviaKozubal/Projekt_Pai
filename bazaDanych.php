<?php // plik sprawdzający czy dana baza danych istnieje, jeżeli nie tworzy ją i zawarte w niej tabele
class bazaDanych {
    private $baza = "sklep.db";
    public $p;

    public function __construct() {
        if (!file_exists($this->baza)) {
            $this->p = new SQLite3($this->baza);
            $this->tworzStrukture();
        } else
            $this->p = new SQLite3($this->baza);
    }

    private function tworzStrukture() {
       // tabela z produktami
        $this->p->exec("CREATE TABLE IF NOT EXISTS produkty (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            nazwa TEXT NOT NULL,
            opis TEXT,
            zdjecie TEXT,
            stan INTEGER DEFAULT 0,
            cena REAL NOT NULL
        )");

        // tabela z metodami wysyłki
        $this->p->exec("CREATE TABLE IF NOT EXISTS wysylka (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            nazwa TEXT NOT NULL,
            koszt REAL NOT NULL,
            czas_dostawy TEXT NOT NULL
        )");

        // dodanie metod wysyłki
        $this->p->exec("INSERT INTO wysylka (nazwa, koszt, czas_dostawy) VALUES 
        ('Kurier przyśpieszony', 19.99, '1-2 dni robocze'), 
        ('Kurier', 14.99, '2-4 dni robocze'), 
        ('Paczkomat', 9.99, '2-4 dni robocze')");
    }
}

$db = new bazaDanych();
?>