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
