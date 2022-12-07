<?php

namespace App\Models;

use MF\Model\Model;

class Usuario extends Model {

	private $id;
	private $nome;
	private $email;
	private $senha;

	public function __get($atributo) {
		return $this->$atributo;
	}

	public function __set($atributo, $valor) {
		$this->$atributo = $valor;
	}

	//salvar
	public function salvar() {

		$query = "insert into usuarios(nome, email, senha)values(:nome, :email, :senha)";
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':nome', $this->__get('nome'));
		$stmt->bindValue(':email', $this->__get('email'));
		$stmt->bindValue(':senha', $this->__get('senha')); //md5() -> hash 32 caracteres
		$stmt->execute();

		return $this;
	}

	//validar se um cadastro pode ser feito
	public function validarCadastro() {
		$valido = true;

		if(strlen($this->__get('nome')) < 3) {
			$valido = false;
		}

		if(strlen($this->__get('email')) < 3) {
			$valido = false;
		}

		if(strlen($this->__get('senha')) < 3) {
			$valido = false;
		}


		return $valido;
	}

	//recuperar um usuário por e-mail
	public function getUsuarioPorEmail() {
		$query = "SELECT nome, email from usuarios where email = :email or nome = :nome";
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':email', $this->__get('email'));
		$stmt->bindValue(':nome', $this->__get('nome'));
		$stmt->execute();

		return $stmt->fetchAll(\PDO::FETCH_ASSOC);
	}

	public function autenticar() {
		$query = 'SELECT id,nome,email,senha from usuarios where email = :email and senha = :senha';
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':email', $this->__get('email'));
		$stmt->bindValue(':senha', $this->__get('senha'));

		$stmt->execute();

		$usuario = $stmt->fetch(\PDO::FETCH_ASSOC);

		if ( isset($usuario['email']) ) { 
			$this->__set('id', $usuario['id']);
			$this->__set('nome', $usuario['nome']);
			$this->__set('email', $usuario['email']);
			$this->__set('senha', $usuario['senha']);
			
			return $this;
		} return false;


	}

	public function getAll($subst = 'u.nome like :nome and '){
		$query = 
		'SELECT 
			u.id, 
			u.nome, 
			u.email,
			(
				select
					count(*)
				from
					usuarios_seguidores as us
				where
					us.id_usuario = :id_usuario and us.id_usuario_seguindo = u.id
			) as seguindo_sn 
		FROM 
			usuarios as u
		WHERE 
			'. $subst .'u.id != :id_usuario 
		ORDER BY
			u.nome ASC';

		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':nome', '%'.$this->__get('nome').'%');
		$stmt->bindValue(':id_usuario', $this->__get('id'));
		$stmt->execute();

		
		return $stmt->fetchAll(\PDO::FETCH_ASSOC);

	}


	public function seguirUsuario ($id_usuario_seguindo) {
		$query = 
		'INSERT into 
			usuarios_seguidores(id_usuario, id_usuario_seguindo) 
		values 
			(:id_usuario, :id_usuario_seguindo)';
		
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':id_usuario', $this->__get('id'));
		$stmt->bindValue(':id_usuario_seguindo', $id_usuario_seguindo);
		$stmt->execute();

		return true;
	}

	public function deixarSeguirUsuario($id_usuario_seguindo) {
		$query = 'DELETE from usuarios_seguidores where id_usuario = :id_usuario and id_usuario_seguindo = :id_usuario_seguindo';
		
		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':id_usuario', $this->__get('id'));
		$stmt->bindValue(':id_usuario_seguindo', $id_usuario_seguindo);
		$stmt->execute();

		return true;
	}

	public function getDados() {

		$query = 
		'SELECT 
			count(*) as seguindo,
			(SELECT count(*) as seguidores from usuarios_seguidores where id_usuario_seguindo = :id_usuario) as seguidores,
			(SELECT count(*) as tweets from tweets where id_usuario = :id_usuario) as tweets,
			(select nome from usuarios where id = :id_usuario) as nome
		from 
			usuarios_seguidores 
		where 
			id_usuario = :id_usuario
		';

		$stmt = $this->db->prepare($query);
		$stmt->bindValue(':id_usuario', $this->__get('id'));
		$stmt->execute();
	
		
		return $stmt->fetch(\PDO::FETCH_ASSOC);
	}

}

?>