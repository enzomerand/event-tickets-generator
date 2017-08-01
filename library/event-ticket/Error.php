<?php
/**
 *  Error Class
 */
 
namespace EventTicket;

/**
 *  Cette classe gère et formatte les erreurs
 *  
 *  @author  Nyzo
 *  @version 1.0.3
 *  @license CC-BY-NC-SA-4.0 Creative Commons Attribution Non Commercial Share Alike 4.0
 */
class Error {
	
	private $error_file;
	
	public function __construct($lang = 'fr') {
	    $this->error_file = ROOT . "/lang/errors/errors-{$lang}.txt";
	}
    
	/**
     * Permet de retourner une erreur formatée
     *
     * @example Erreur en brute dans le fichier (à la ligne 25 pour l'exemple) :
     *              Bonjour %s, vous êtes disponible pendant %d minutes
     *
     *         On peut appeler la fonction suivante :
     *             <code>
     *                 $this->Error->getError(25, ['Jean', 5], '!');
     *             </code>
     *
     *         Erreur en brute dans le fichier (à la ligne 26 pour l'exemple) :
     *              Impossible d'accéder à la page
     *
     *         On peut appeler la fonction suivante :
     *             <code>
     *                 $this->Error->getError(26);
     *             </code>
     *
     * @see    sprintf()
     * @param  int    $code   Code de l'erreur
     * @param  array  $vars   Variables dynamiques à passer
     * @param  string $end    Dernier caractère de la chaîne
	 *
     * @return string
     */
    public function getError($code, $vars = []){
        if(!file_exists($this->error_file)){
            return "Erreur ! Le fichier d'erreur \"{$this->error_file}\" est introuvable.";
        }

        if(ctype_digit($code) || is_int($code)){
		    $file = fopen($this->error_file, 'r');
            $i = 0;

            while ($i < $code){
                $error = fgets($file);
                ++$i;
            }
            fclose($file);

            if(isset($vars[0]))
                $error = vsprintf($error, $vars);

	        return trim($error);
		}
		
		exit();
    }

	/**
     * Permet d'afficher une erreur au lieu de la retourner
     *
     * @see    getError()
     * @param  int    $code   Code de l'erreur
     * @param  array  $vars   Variables dynamiques à passer
     * @param  string $end    Dernier caractère de la chaîne
     */
    public function echoError($code, $vars = []){
        echo $this->getError($code, $vars);
    }
	
}