<?php

return [
    'new-year'                => '= 01-01 on Monday,Tuesday,Wednesday,Thursday,Friday', // New Year's Day
    'new-year-next-day'       => '= 01-02 on Monday', // New Year's Day (observance)
    'new-year-previous-day'   => '= 12-31 on Friday', // New Year's Day (observance)
    'mlk-day'                 => '= third Monday of January', // Birthday of Martin Luther King, Jr.
    'washingtons-birthday'    => '= third Monday of February', // Washington's Birthday (Presidents' Day)
    'memorial-day'            => '= last Monday of May', // Memorial Day
    'juneteenth'              => '= 06-19 if year > 2021 on Monday,Tuesday,Wednesday,Thursday,Friday', // Juneteenth National Independence Day
    'juneteenth-first'        => '= 06-19 if year = 2021', // Juneteenth (first occurrence)
    'juneteenth-next-day'     => '= 06-20 if year > 2021 on Monday', // Juneteenth (observance)
    'juneteenth-previous-day' => '= 06-18 if year > 2021 on Friday', // Juneteenth (observance)
    'independence-day'        => '= 07-04 if Saturday then previous Friday and if Sunday then substitute', // Independence Day
    'labor-day'               => '= first Monday of September', // Labor Day
    'columbus-day'            => '= second Monday of October', // Columbus Day
    'veterans-day'            => '11-11', // Veterans Day
    'thanksgiving'            => '= fourth Thursday of November', // Thanksgiving Day
    'christmas'               => '= 12-25 if Saturday then previous Friday and if Sunday then substitute', // Christmas Day
    //    '02-14'                                                                 => '02-14',
    //    '04-15' => '= 04-15 if Friday then next Monday and if Saturday,sunday then next Tuesday',
    //    'wednesday-before-04-28'                                                => '= Wednesday before 04-28',
    //    '2nd-sunday-in-may'                                                     => '= second Sunday of May',
    //    '3rd-sunday-in-june'                                                    => '= third Sunday of June',
    //    '10-31-18:00'                                                           => '= 10-31 18:00',
    //    'tuesday-after-1st-monday-in-november-every-4-years-since-1848'         => '= Tuesday after first Monday of November every 4 years since 1848',
    //    '4th-thursday-in-november'                                              => '= fourth Thursday of November',
    //    'friday-after-4th-thursday-in-november'                                 => '= Friday after fourth Thursday of November',
    //    '12-24'                                                                 => '12-24',
    //    '12-31'                                                                 => '12-31',
];
