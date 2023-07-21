<?php

/**
 * Class SearchModel
 */
class SearchModel extends Model
{
    static $forbidden = ['%','*'];
    static $mapping   = [
        'tristan' => 'https://youtu.be/XGsI7M3Pjis?t=51',
        'Tristan' => 'https://youtu.be/XGsI7M3Pjis?t=51',
        'kipknots' => 'https://youtu.be/Eewe2bhg49o',
        'Kipknots' => 'https://youtu.be/Eewe2bhg49o'
    ];

    var $table_name = 'search';
}