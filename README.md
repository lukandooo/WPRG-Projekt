# Quizz-ly - Platforma Quizowa 🎓

## O projekcie
Projekt Quizz-ly został stworzony w ramach przedmiotu Warsztaty Programistyczne (WPRG) na II semestrze studiów. Aplikacja to w pełni funkcjonalna platforma internetowa umożliwiająca rozwiązywanie oraz tworzenie własnych zestawów pytań. 

Projekt (oceniony na 73%) stanowi ważne podsumowanie moich początków z programowaniem webowym. Głównym założeniem było praktyczne wykorzystanie języka PHP do budowy logiki backendowej, autoryzacji opartej o sesje oraz sprawnego zarządzania systemem plików. Zamiast standardowej relacyjnej bazy danych, aplikacja przechowuje użytkowników, wyniki oraz treści quizów w systemie ustrukturyzowanych plików tekstowych.

## Funkcjonalności
* **Rozwiązywanie quizów**: Użytkownicy mogą sprawdzać swoją wiedzę w kategoriach takich jak Popkultura, Sport, Geografia i Motoryzacja.
* **Różnorodne typy pytań**: System obsługuje wpisywanie tekstu z klawiatury (text input), pytania jednokrotnego i wielokrotnego wyboru, a także zadania oparte na odgadywaniu obrazków (image guess).
* **Zarządzanie użytkownikami**: Pełny system logowania i rejestracji (hasła są bezpiecznie hashowane za pomocą `password_hash()`) oraz możliwość wgrywania własnych zdjęć profilowych.
* **Śledzenie wyników**: Każdy profil użytkownika monitoruje statystyki poprawnych odpowiedzi i przelicza skuteczność dla poszczególnych kategorii. Dostępna jest też opcja eksportu wyników do pliku tekstowego.
* **Kreator quizów**: Zalogowani użytkownicy mogą dynamicznie dodawać własne quizy, układając pytania, wprowadzając opcje odpowiedzi i przesyłając obrazy z dysku.

## Wykorzystane Technologie
* **Backend**: PHP (sesje, obsługa żądań POST/GET, operacje I/O na plikach, weryfikacja plików uploadowanych na serwer).
* **Frontend**: HTML5, CSS3.
* **Baza Danych**: Projekt wykorzystuje pliki `.txt` jako bazę danych do przechowywania układu aplikacji i stanu (m.in. konfiguracje dla poszczególnych tabel quizów i użytkowników).

## Jak uruchomić projekt lokalnie?
1. Sklonuj repozytorium na swój komputer.
2. Przenieś pobrany folder do odpowiedniego katalogu serwera obsługującego PHP (np. `htdocs` w środowisku XAMPP lub `www` dla WAMP).
3. **Ważne:** Upewnij się, że Twój serwer ma uprawnienia do zapisu (odczyt/zapis) dla folderów `wyniki/`, `QUIZY/` oraz `zdjeciaProfilowe/`, ponieważ aplikacja dynamicznie generuje i zapisuje tam nowe pliki.
4. Uruchom lokalny serwer i w przeglądarce wpisz: `http://localhost/nazwa_folderu_z_projektem/logowanie.php`.

## Przemyślenia z perspektywy czasu (Możliwy kierunek rozwoju)
Traktuję ten projekt jako ważny etap edukacyjny. Gdybym pisał tę aplikację ponownie dzisiaj, z moją obecną wiedzą, wprowadziłbym następujące zmiany:
* Zastąpienie operacji na plikach tekstowych na rzecz pełnoprawnej relacyjnej bazy danych (np. MySQL / PostgreSQL).
* Refaktoryzację kodu w oparciu o architekturę MVC (Model-View-Controller) w celu lepszego oddzielenia logiki biznesowej od warstwy prezentacji.
* Wdrożenie zabezpieczeń przed atakami typu SQL Injection (w przypadku dodania bazy) oraz XSS, poprzez bardziej zaawansowaną walidację i sanityzację przesyłanych formularzy.
