VUT FIT - IIS projekt
=====================

Zadání
------

Úkolem zadání je vytvořit informační systém pro rezervaci vstupenek na kulturní události (promítání filmů, prezentace přednášky, divadelní představení, apod.) pořádané ve spravovaných sálech. Každý sál má nějaké označení, pomocí kterého ho jeho návštěvníci budou moci vhodně odlišit a další atributy (např. adresa, schéma sedadel, apod.). Každý sál má pevně stanovený počet sedadel, kde každé sedadlo je definováno řadou a číslem sedadla v řadě; lze uvažovat jako matici [řada, sedadlo]). Každá kulturní událost se koná v nějakém sále, v nějaký časový interval a stojí nějakou cenu. Kulturní událost (promítání filmu, prezentace přednášky, divadelní představení) reprezentuje jednu instanci nějakého kulturního díla (film, přednáška, divadelní inscenace apod.), které se může opakovaně konat ve více sálech. Toto kulturní dílo je charakterizováno popisem: název, typ (film, přednáška, divadelní inscenace, apod.), obrázek, žánr (tagy), účinkující, hodnocení, apod. Uživatelé budou moci informační systém použít jak pro vkládání sálů, kulturních děl a událostí, tak pro rezervaci míst na akci - a to následujícím způsobem:

- administrátor:
  spravuje uživatele
  má rovněž práva všech následujících rolí:
- redaktor:
  vkládá a spravuje sály, kulturní díla a události a obsah, který je prezentován divákům a neregistrovaným návštěvníkům
  k popisu kulturního díla může nahrát obrázky
  má rovněž práva diváka
- pokladní:
  registruje a spravuje rezervace pro vybrané sály, po úhradě vstupného potvrdí rezervaci a případně vydá vstupenky (pokud je hrazeno na místě)
  má rovněž práva diváka
- divák:
  rezervuje 1 až n míst (zvolte vhodné omezení - např. počet sedadel, případně vyžadovaná úhrada v daném intervalu - kontroluje a případně ruší pokladní)
  sleduje stav jeho rezervací (provedení úhrady - viz role pokladní)
  má rovněž práva (a, b) neregistrovaného návštěvníka
- neregistrovaný návštěvník:
  (a) vyhledává a prohlíží kulturní události dle různých vlastností
  (b) má možnost vhodně vidět, která sedadla jsou zabraná (v případě souběžné rezervace je pomalejší uživatel vhodně upozorněn)
  může provést rezervaci 1 až n míst bez registrace: vyžadujte vhodné údaje (má možnost dokončit registraci a stát se divákem) 

Implementace
------------

Projekt je implementován v PHP 7, je použitý framework Symfony.
