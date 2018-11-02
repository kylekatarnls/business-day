<?php

/**
 * Slovenian holidays.
 *
 * Days that are commented are holidays, but not work-free-days.
 * Source: http://www.vlada.si/o_sloveniji/politicni_sistem/prazniki/
 */
return array(
    'new-year'          => '01/01', // novo leto
    'new-year-next-day' => '02/01', // novo leto
    'preseren-day'      => '08/02', // Prešernov dan, slovenski kulturni praznik
    'rebellion-day'     => '27/04', // dan upora proti okupatorju
    'vacation-day'      => '01/05', // praznik dela
    'vacation-next-day' => '02/05', // praznik dela
//  '08/06', // dan Primoža Trubarja
    'national-day' => '25/06', // dan državnosti
    'assumption'   => '15/08', // Marijino vnebovzetje
//  '17/08', // združitev prekmurskih Slovencev z matičnim narodom
//  '15/09', // vrnitev Primorske k matični domovini
//  '25/10', // dan suverenosti
    'reformation-day' => '31/10', // dan reformacije
    'memorial-day'    => '01/11', // dan spomina na mrtve
//  '23/11', // dan Rudolfa Maistra
    'christmas'        => '25/12', // božič
    'independence-day' => '26/12', // dan samostojnosti in enotnosti

    // velika noč
    'easter' => function ($year) {
        $days = easter_days($year);
        $date = new DateTime("$year-03-21 +$days days");

        return $date->format('d/m');
    },
    // velikonočni ponedeljek
    'easter-monday' => function ($year) {
        $days = easter_days($year) + 1;
        $date = new DateTime("$year-03-21 +$days days");

        return $date->format('d/m');
    },
    // binkoštna nedelja - binkošti
    'pentecost' => function ($year) {
        $days = easter_days($year) + 49;
        $date = new DateTime("$year-03-21 +$days days");

        return $date->format('d/m');
    },
);
