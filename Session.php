<?php
    /**
    * Name:		Session
    * Type:		Help
    * Function:	Configurar a sessão do usuário
    *
    * @author      Rodrigo Soares <rodrigo.s.ferreira96@gmail.com>
    * @version     1.0
    * @see         http://www.rodrigosoares.esy.es/
    */
    class Session {
        
        private $time;      // Data e hora que iniciou a sessão
        private $cache;     // Tempo de acesso, o pré definido é 30 minutos
        private $browser;   // Navegadores

        /** Inicia a sessão @var $cache recebe inteiros de minutos  */
        function __construct(int $cache = null){
            session_start();
            $this->StartSession($cache);
            $this->CheckCookie();
        }//endFunction
        
        
        // AREA DE SESSÃO DO USUARIO
        
        /** Inicia os dados da sessão do usuário */
        private function StartSession($cache){
            $this->time     = date('Y-m-d H:i:s');
            $this->cache    = ($cache ? $cache : 30);
            
            $this->CheckBrowser(); // Identifica o navegador
            
            if(empty($_SESSION['traffic'])){
                $this->CreateSession();
            }else{
                $this->UpdateSession();
            }
            
        }//endFunction

        /** Cria a sessão do usuário */
        private function CreateSession(){
            $_SESSION['traffic'] = [
                'ses_id'        => session_id(),
                'ses_start'     => $this->time,
                'ses_end'       => date('Y-m-d H:i:s', strtotime("+{$this->cache}minutes")),
                'ses_count'     => 0,
                'ses_ip'        => filter_input(INPUT_SERVER, 'REMOTE_ADDR', FILTER_VALIDATE_IP),
                'ses_url'       => filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_DEFAULT),
                'ses_browser'   => $this->browser,
                'ses_user'      => null
            ];
        }//endFunction

        /** Atualiza o tempo de acesso do usuário */
        private function UpdateSession(){
            // Exclui a sessão do usuário se passar o tempo do cache
            if($this->time > $_SESSION['traffic']['ses_end']){
                unset($_SESSION['traffic']);
                session_destroy();
            }else{                
                $_SESSION['traffic']['ses_count']++;
                $_SESSION['traffic']['ses_end'] = date('Y-m-d H:i:s', strtotime("+{$this->cache}minutes"));
                $_SESSION['traffic']['ses_url'] = filter_input(INPUT_SERVER, 'REQUEST_URI', FILTER_DEFAULT);
            }
        }//endFunction

        /** Identifica navegador do usuário! */
        private function CheckBrowser() {
            $this->browser = filter_input(INPUT_SERVER, "HTTP_USER_AGENT", FILTER_DEFAULT);
            if (strpos($this->browser, 'Chrome')):
                $this->browser = 'Chrome';
            elseif (strpos($this->browser, 'Firefox')):
                $this->browser = 'Firefox';
            else:
                $this->Browser = 'Outros';
            endif;
        }//endFunction

        /** Cria o cookie do usuário */
        private function CheckCookie(){
            if($_SESSION['traffic']['ses_user'] !== null){
                setcookie('email', $_SESSION['traffic']['ses_user']['email'], time() + 86400);
                setcookie('pass', $_SESSION['traffic']['ses_user']['pass'], time() + 86400);
            }
        }//endFunction

    }