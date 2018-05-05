<?php

// http://www.vlada.si/o_sloveniji/politicni_sistem/prazniki/
return array(
    '01/01', // novo leto
    '02/01', // novo leto
    '08/02', // Prešernov dan, slovenski kulturni praznik
    '27/04', // dan upora proti okupatorju
    '01/05', // praznik dela
    '02/05', // praznik dela
//  '08/06', // dan Primoža Trubarja
    '25/06', // dan državnosti
    '15/08', // Marijino vnebovzetje
//  '17/08', // združitev prekmurskih Slovencev z matičnim narodom
//  '15/09', // vrnitev Primorske k matični domovini
//  '25/10', // dan suverenosti
    '31/10', // dan reformacije
    '01/11', // dan spomina na mrtve
//  '23/11', // dan Rudolfa Maistra
    '25/12', // božič
    '26/12', // dan samostojnosti in enotnosti

    function ($year) { // velika noč
        $days = easter_days($year);
        $date = new DateTime("$year-03-21 +$days days");

        return $date->format('d/m');
    },
    function ($year) { // velikonočni ponedeljek
        $days = easter_days($year) + 1;
        $date = new DateTime("$year-03-21 +$days days");

        return $date->format('d/m');
    },
    function ($year) { // binkoštna nedelja - binkošti
        $days = easter_days($year) + 49;
        $date = new DateTime("$year-03-21 +$days days");

        return $date->format('d/m');
    },
);
