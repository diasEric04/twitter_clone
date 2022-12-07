<?php

namespace App\Models;

use MF\Model\Model;

class Tweet extends Model {

	private $id;
	private $id_usuario;
	private $tweet;
	private $data;

    public function __get($atributo) {
		return $this->$atributo;
	}

	public function __set($atributo, $valor) {
		$this->$atributo = $valor;
	}

    public function salvar() {

        $query = 'insert into tweets(id_usuario, tweet) values(:id_usuario, :tweet)';

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $this->__get('id_usuario'));
        $stmt->bindValue(':tweet', $this->__get('tweet'));
        $stmt->execute();

        return $this;
    }

    public function validarTweet(){
        return strlen($this->tweet) > 0;
    }

    public function getAll() {

        $query = 
        'SELECT 
            t.id, t.tweet, t.id_usuario, DATE_FORMAT(t.data, "%d/%m/%Y %H:%i") as data, u.nome AS nome_usuario 
        FROM 
            tweets AS t LEFT JOIN usuarios AS u ON(t.id_usuario = u.id)
        WHERE
            t.id_usuario = :id_usuario
            or
            t.id_usuario in (select id_usuario_seguindo from usuarios_seguidores where id_usuario = :id_usuario)
        ORDER BY
            t.data DESC';

        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_usuario', $this->id_usuario);
        $stmt->execute();

        $tweets = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        
        foreach( $tweets as $key => $value) {
            $diff_tempo = strtotime(date('Y/m/d H:i:s')) - strtotime(substr(explode('/', $value['data'])[2], 0, 4).'/'.explode('/', $value['data'])[1].'/'.explode('/', $value['data'])[0].' '.substr(explode('/', $value['data'])[2], 5, 6)) ;

            $tweets[$key]['diff_segundos'] = $diff_tempo;
            $tweets[$key]['diff_minutos'] = floor($diff_tempo / 60);
            $tweets[$key]['diff_horas'] = floor($diff_tempo / 60 / 60);
            $tweets[$key]['diff_dias'] = floor($diff_tempo / 60 / 60 / 24);
            $tweets[$key]['hora'] = substr(explode('/', $value['data'])[2], 5, 6);

        }

        return $tweets;

       
    }

    public function remover() {
        $query =
        'DELETE from tweets where id = :id_tweet';
        $stmt = $this->db->prepare($query);
        $stmt->bindValue(':id_tweet', $this->id);
        $stmt->execute();

        return true;
    }
}

?>