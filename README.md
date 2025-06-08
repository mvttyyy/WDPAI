# StudyWise - Portal do korepetycji językowych

## Opis projektu

StudyWise to platforma webowa wspierająca korepetycje językowe, która umożliwia uczniom i nauczycielom rejestrację, logowanie, przeglądanie ofert, wybór nauczycieli, prowadzenie czatów oraz zarządzanie profilami i relacjami. System posiada panel administracyjny do zarządzania użytkownikami i rolami, a całość została zbudowana w czystym PHP 8+ z wykorzystaniem PostgreSQL, Docker oraz serwera Apache, zapewniając responsywny interfejs i wygodę obsługi na komputerach oraz urządzeniach mobilnych.

---

## Instalacja

1. Sklonuj repozytorium:
```git clone <repo-url> cd StudyWise```
2. Zbuduj i uruchom kontenery:
```docker-compose up --build```


---

## Funkcje

- **Lista nauczycieli:** Przeglądanie nauczycieli wraz z informacjami o językach, kraju, cenie i zdjęciu profilowym.
- **Wyszukiwanie i filtrowanie:** Wyszukiwanie nauczycieli po imieniu, nazwisku lub języku; filtrowanie po języku nauczania.
- **Wybór nauczyciela:** Dodawanie nauczycieli do listy wybranych przez ucznia.
- **Czat:** Dwustronna komunikacja tekstowa między uczniem a nauczycielem, z historią wiadomości.
- **Zarządzanie profilem:** Edycja danych użytkownika, uzupełnianie profilu nauczyciela, dodawanie zdjęcia, wybór języków i ustalanie ceny.
- **Panel administratora:** Przeglądanie użytkowników, zmiana ról, usuwanie kont, podgląd profili.
- **Responsywny interfejs:** Dostosowanie do urządzeń mobilnych i desktopów.
- **Autocomplete:** Podpowiedzi w polu wyszukiwania nauczycieli. (nie dynamicznie)
- **Bezpieczeństwo:** Uwierzytelnianie użytkowników, role i uprawnienia (RBAC), ochrona endpointów, hashowanie haseł, parametryzowane zapytania SQL.
- **Upload zdjęć:** Możliwość dodania zdjęcia profilowego przez nauczyciela.
- **Widoki i statystyki:** Podsumowanie czatów, lista wybranych nauczycieli, historia wiadomości.

---

## Zabezpieczenia

Hasła użytkowników są bezpiecznie hashowane przy użyciu bcrypt, a wszystkie zapytania do bazy realizowane są z użyciem parametrów, co chroni przed SQL Injection. Sesje użytkowników są zarządzane z wykorzystaniem PHP. Integralność danych zapewniają klucze obce z ON DELETE CASCADE, a usuwani użytkownicy są archiwizowani w osobnej tabeli przez wyzwalacz bazy danych.

---

## Scenariusze użycia

Uczeń rejestruje się, przegląda nauczycieli, wybiera nauczyciela i prowadzi z nim czat. Nauczyciel uzupełnia profil, dodaje oferty językowe i zarządza swoimi uczniami. Administrator przegląda użytkowników, zmienia role oraz usuwa konta.

---

## Zaimplementowane funkcje

- **Server Side Rendering:** Wszystkie główne widoki (lista nauczycieli, profil, czat, panel admina) są renderowane po stronie serwera w PHP.
- **Client Side Rendering:** Wybrane akcje, takie jak usuwanie nauczyciela z listy ucznia, podpowiedzi wyszukiwania (autocomplete), dynamiczne odświeżanie wiadomości w czacie, realizowane są przez JavaScript (Fetch API).
- **AJAX endpoints:** Usuwanie nauczyciela z listy ucznia obsługiwane jest przez endpointy PHP zwracające odpowiedzi JSON (`/index.php?page=remove-teacher`).
- **Komponenty PHP:** Widoki korzystają z komponentów (np. `header.php`), do których przekazywane są dynamiczne dane (rola, status logowania).
- **Autoryzacja i ochrona ścieżek:** Logowanie, wylogowanie, sprawdzanie ról i sesji użytkownika (np. dostęp do panelu admina, czatu, profilu).
- **CRUD:** 
  - Użytkownicy: rejestracja, logowanie, edycja profilu, usuwanie (admin).
  - Nauczyciele: tworzenie i edycja profilu, dodawanie ofert językowych, upload zdjęcia.
  - Uczniowie: wybór i usuwanie nauczycieli z listy.
  - Czat: tworzenie czatu, wysyłanie i pobieranie wiadomości.
- **Relacje bazodanowe:**
  - 1:1 – `users` ↔ `teacher_profiles`, `users` ↔ `student_profiles`
  - 1:N – `users` ↔ `chats` (student_id, teacher_id), `chats` ↔ `chat_messages`
  - N:M – `users` ↔ `selected_teachers` ↔ `users`
  - 1:N – `teacher_profiles` ↔ `teacher_offers`
- **Panel administratora:** Przeglądanie użytkowników, zmiana ról, usuwanie kont, podgląd profili.
- **Widoki i statystyki:** Podsumowanie czatów, lista wybranych nauczycieli, historia wiadomości.

## Struktura bazy danych

### Tabele

auth, users, roles, languages, teacher_profiles, teacher_offers, student_profiles, selected_teachers, chats, chat_messages, deleted_users

---

### Funkcje SQL

archive_deleted_user(), capitalize_user_name(), insert_welcome_message(), update_teacher_profiles_updated_at(), count_messages_in_chat(chatid), user_chat_count(uid)

---

### Wyzwalacze (Triggers)

trg_archive_deleted_user, trg_capitalize_user_name, trg_teacher_profiles_updated_at, trg_welcome_message

---

### Widoki

user_chat_overview, user_selected_teachers, view_deleted_users, view_latest_chat_message, view_teacher_timestamps

---

## Technologie

- Figma
- HTML
- CSS
- JavaScript
- Docker
- PostgreSQL
- PHP

## Diagram ERD




