<?=

$tweet['diff_segundos'] <= 59 ? // se o tempo de postagem for entre 1 minuto
    'agora mesmo' : 

( $tweet['diff_minutos'] >= 1 && $tweet['diff_minutos']  <= 59 ? //se o tempo for entre 1 hora
    'há '. $tweet['diff_minutos'] . ($tweet['diff_minutos'] == 1 ? ' minuto' : ' minutos') : 

( $tweet['diff_horas'] >= 1 && $tweet['diff_horas']  <= 23 ? //se o tempo for mais q 1 hora e menor 1 dia
    'hoje ás ' . $tweet['hora'] : 

($tweet['diff_dias'] === 1 ? //no dia seguinte da mensagem
    'ontem ás ' . $tweet['hora'] : 

($tweet['diff_dias']  === 2 ?  // dois dias seguintes da mensagem
    'anteontem ás ' . $tweet['hora'] : 

        $tweet['data'])))) //caso contrario, data do tweet

?>