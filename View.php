<?php
    
    /**
    * Name:		View
    * Type:		Help
    * Function: Selecionar ou requisar arquivos templates de html ou php
    *
    * @author      Rodrigo Soares <rodrigo.s.ferreira96@gmail.com>
    * @version     1.0
    * @see         http://www.rodrigosoares.esy.es/
    * @example     No arquivo template as variáveis tem que ser assim = #variavel# 
    */

    class View {

        private static $data;       // Dados em array
        private static $keys;       // Keys do array
        private static $values;     // Values do array
        private static $template;   // Arquivo template a ser chamado

        // PUBLIC METHODS
        
        /** Retorna o arquivo template em View*/
        public static function ShowTemplate(string $template, array $data){
            self::ConvertTemplate($template);
            self::SetKeys($data);
            self::SetValues();
            self::ShowViews();
        }//endFunction

       /** @var $file, recebe o caminho e o arquivo sem extensão, @var $data, recebe os dados geralmente do banco. ex.: ('_mvc/arquivo', ['nome' => 'rodrigo', 'senha' => '1234']) */
        public static function ShowRequest(string $file, array $data){
            extract($data); // Transforma todos os dados em variáveis
            require $file . '.php';
        }//endFunction
        
        // PRIVATE METHODS

        /** Adiciona a extensão no template informado e retorna o conteúdo em string*/
        private static function ConvertTemplate($template){
            self::$template = $template . '.html'; // A extensão padrão vai ser 'html' para arquivos de templates
            if(file_exists(self::$template)){
                self::$template = file_get_contents(self::$template); // Lê o conteudo do arquivo e transforma em string, para exibir, basta dar echo
            }else{
                die('O arquivo "' . self::$template . '" não foi encontrado');
            }
        }//endFunction

        /** Converte os dados para o padrão de valor a ser chamado */
        private static function SetKeys($data){
            self::$data = $data; // Recebe os dados em array
            self::$keys = '#' . implode('#&#', array_keys($data)) . '#'; // Transforma o array em string, adiciona a '#' no começo e fim de cada key e  separa com '&'
            self::$keys = explode('&', self::$keys); // Transforma a string em array com a '#' devidamente implantada
        }//endFunction

        /** Captura os values do array @var self::$data */
        private static function SetValues(){
            self::$values = array_values(self::$data);
        }//endFunction

        /** Retorna o arquivo convertido */
        private static function ShowViews(){
            echo str_replace(self::$keys, self::$values, self::$template);
        }//endFunction
    }