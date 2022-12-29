# Zadanie rekrutacyjne

Stwórz obiektowy mechanizm (model) koszyka zakupowego w oparciu o testy (TDD).
 Twoim zadaniem jest napisanie klas: `Product` (_produkt_), `Item` (_pozycja w
 koszyku_), `Cart` (_koszyk_) oraz `Order` (_zamówienie_). Dodatkowe wytyczne:

* każdy produkt ma swoją nazwę i cenę,
* podczas dodawania produktu do koszyka użytkownik podaje liczbę zamawianych
 sztuk (ang. _quantity_),
* produkty mają zdefiniowaną minimalną liczbę sztuk, jaką można zamówić;
 domyślnie dla każdego produktu powinna ona wynosić **1**; jeżeli użytkownik
 wybierze mniejszą ilość, należy zwrócić błąd (wyjątek),
* koszyk powinien operować na groszach – żeby uniknąć błędów operacji
 zmiennoprzecinkowych,
* kod powinien być zgodny z "Czystą Architekturą" (ang. _Clean Architecture_),
 w szczególności zwracając uwagę na zarządzanie wyjątkami, poprawne nazewnictwo
 metod i SRP.

Aby uprościć zadanie, nie przejmuj się przechowywaniem koszyka w sesji ani w
 bazie danych. Nie musisz także pisać kontrolerów, ani widoków. Zadanie polega
 wyłącznie na stworzeniu modelu.

#### Dodatkowo punktowane zadanie

Do stworzonego mechanizmu dodaj testy i ich implementację. Warunki zadania:

Przy każdym produkcie wprowadź stawkę podatku (ang. _tax_) w wysokości 0%, 5%,
 8% lub 23%, co umożliwi wyliczenie wartości brutto tego produktu.

Dodatkowo dodaj możliwość pobrania wartości brutto (ang. _gross_) wszystkich
 produktów w koszyku poprzez metodę `getTotalPriceGross()`. Zmodyfikuj także
 odpowiedź `getDataForView()` w klasie `Order` w taki sposób, aby każdy produkt
 miał podaną stawkę podatku (_string_, np. `23%`) oraz cenę brutto i sumę
 koszyka brutto.

"# php-busket-task" 
