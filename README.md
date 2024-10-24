# Aplikacja do śledzenia czasu pracy

## Opis projektu

To repozytorium zawiera rozwiązanie zadania rekrutacyjnego na stanowisko PHP Developera w firmie Innovation Software.
Jest to aplikacja napisana w najnowszej stabilnej wersji **Symfony/Laravel** oraz **PHP**, z wykorzystaniem **MariaDB/MySQL**. Aplikacja umożliwia zarządzanie czasem pracy pracowników, w tym rejestrację rozpoczęcia i zakończenia pracy, a także obliczanie przepracowanych godzin i wynagrodzeń, w tym nadgodzin.

## Założenia projektu

- Aplikacja została napisana na najnowszej stabilnej wersji **Symfony/Laravel**.
- Wykorzystano najnowszą wersję **PHP**.
- Zastosowano najnowszą stabilną wersję **MariaDB/MySQL**.
- Zadbano o odpowiednią jakość kodu, zgodną z ogólnymi standardami programistycznymi.
- Wykorzystano wzorce projektowe, które najlepiej odpowiadają założeniom zadania.

## Funkcjonalności

1. **Zarządzanie pracownikami**:
   - Tworzenie pracownika z unikalnym identyfikatorem UUID.
   - Endpointy do tworzenia i zarządzania pracownikami.

2. **Rejestracja czasu pracy**:
   - Dodawanie rekordów czasu pracy dla pracownika.
   - Sprawdzanie poprawności danych (np. brak nakładających się przedziałów czasu pracy).
   - Ograniczenie rejestrowanego czasu pracy do 12 godzin na jedną zmianę.

3. **Podsumowanie czasu pracy**:
   - Możliwość uzyskania podsumowania przepracowanych godzin za dzień lub miesiąc.
   - Obliczanie wynagrodzenia z uwzględnieniem nadgodzin (stawka nadgodzinowa to 200%).

## Struktura aplikacji

### Encje

1. **Pracownik**:
   - UUID (unikalny identyfikator)
   - Imię
   - Nazwisko

2. **Czas pracy**:
   - Relacja do pracownika (w oparciu o UUID)
   - Data i godzina rozpoczęcia
   - Data i godzina zakończenia
   - Dzień rozpoczęcia (służy do obliczania godzin dla danego dnia)

### Endpointy

1. **Tworzenie pracownika**:
   - Endpoint: `/api/pracownik/tworzenie`
   - Input: `{"imię": "Karol", "nazwisko": "Szabat"}`
   - Output: `{"id": "unikalny identyfikator"}`

2. **Rejestracja czasu pracy**:
   - Endpoint: `/api/czas_pracy/dodaj`
   - Input: `{"unikalny identyfikator pracownika": "UUID", "data i godzina rozpoczęcia": "1970-01-01 08:00", "data i godzina zakończenia": "1970-01-01 14:00"}`
   - Output: `{"response": "Czas pracy został dodany!"}`

3. **Podsumowanie czasu pracy - dzień**:
   - Endpoint: `/api/czas_pracy/podsumowanie_dzien`
   - Input: `{"unikalny identyfikator pracownika": "UUID", "data": "1970-01-01"}`
   - Output: `{"suma po przeliczeniu": "120 PLN", "ilość godzin": 6, "stawka": "20 PLN"}`

4. **Podsumowanie czasu pracy - miesiąc**:
   - Endpoint: `/api/czas_pracy/podsumowanie_miesiac`
   - Input: `{"unikalny identyfikator pracownika": "UUID", "data": "1970-01"}`
   - Output: `{"ilość normalnych godzin": 40, "ilość nadgodzin": 8, "suma po przeliczeniu": "1120 PLN"}`


## Konfiguracja

1. **Norma godzin miesięcznych**: 40
2. **Stawka godzinowa**: 20 PLN
3. **Stawka nadgodzinowa**: 200% stawki

## Instalacja

1. Sklonuj repozytorium:
```bash
   git clone https://github.com//EugeneBachura/WorkTimeManager
```
2. Zainstaluj zależności:
```bash
   composer install
```
3. Skonfiguruj plik .env z danymi do bazy danych.
4. Uruchom migracje:
```bash
   php artisan migrate
```
5. Uruchom serwer aplikacji:
```bash
   php artisan serve
```
