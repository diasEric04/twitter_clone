<?php 

namespace App\Controllers;

//os recursos do miniframework
use MF\Controller\Action;
use MF\Model\Container;

class AppController extends Action {
    
    public function timeline() {
        $this->validaAutenticacao();

        $tweet = Container::getModel('Tweet');
        $tweet->__set('id_usuario', $_SESSION['id']);
        $tweets = $tweet->getAll();

        $this->view->tweets = $tweets;
        $this->view->dados_usuarios = $this->renderDados();

        $this->render('timeline'); 

    }

    public function tweet() {
        $this->validaAutenticacao();

        $tweet = Container::getModel('Tweet');

        $tweet->__set('tweet', $_POST['tweet']);
        $tweet->__set('id_usuario', $_SESSION['id']);

        if ($tweet->validarTweet()) {

            $tweet->salvar(); 
            header('Location: /timeline');
        } else header('Location: /timeline?status=erro');
        

        

    }

    public function quem_seguir(){
        $this->validaAutenticacao();

        $this->renderConteudoQuemSeguir(1);

        $this->render('quem_seguir');
    }

    public function content_quem_seguir() {
        $this->validaAutenticacao();

        $this->renderConteudoQuemSeguir(2);

        $this->render('content_quem_seguir');
    }


    public function acao() {
        $this->validaAutenticacao();

        $acao = isset($_GET['acao']) ? $_GET['acao'] : '';
        $id_usuario_seguindo = isset($_GET['id_usuario']) ? $_GET['id_usuario'] : '';

        $usuario = Container::getModel('Usuario');
        $usuario->__set('id',$_SESSION['id']);

        if( $acao === 'seguir') {
            
            if ($usuario->seguirUsuario($id_usuario_seguindo)) header('Location: /quem_seguir?user_procurado='.$_GET['user_procurado']);
            else echo 'erro ao seguir';
            
        } else if ($acao === 'deixar_de_seguir') {

            if ($usuario->deixarSeguirUsuario($id_usuario_seguindo)) header('Location: /quem_seguir?user_procurado='.$_GET['user_procurado']);
            else echo 'erro ao deixar de seguir';

        }
    }

    public function remover_tweet() {
        $this->validaAutenticacao();

        $tweet = Container::getModel('Tweet');
        $tweet->__set('id', $_POST['id_tweet']);

       if ( $tweet->remover() ) header('Location: /timeline');
       else echo 'erro ao apagar tweet';
        
    }

    //======================


    public function renderDados(){
        $usuario = Container::getModel('usuario');
        $usuario->__set('id', $_SESSION['id']);
  
        return $usuario->getDados();
    }

    public function renderConteudoQuemSeguir( $opcao ) {
        
        $teste_bool = !empty($_GET['user_procurado']);
        $usuario = Container::getModel('usuario');

        $usuario->__set('nome', $teste_bool ? $_GET['user_procurado'] : '');
        $usuario->__set('id', $_SESSION['id']);   
        
        $usuarios = $teste_bool ? $usuario->getAll() : $usuario->getAll('');   

        $this->view->nome_procurado = $usuario->__get('nome');
        $this->view->usuarios = $usuarios; 
        if($opcao === 1) $this->view->dados_usuarios = $this->renderDados();

    }

    public function validaAutenticacao(){
        session_start();
         if(empty($_SESSION['id']) && empty($_SESSION['nome'])) {
            header('Location: /?login=erro');
         }
    }
    


    
}


?>