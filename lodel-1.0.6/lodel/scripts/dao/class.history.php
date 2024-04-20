<?php
/**
 * LODEL - Logiciel d'Édition ÉLectronique.
 * @license GPL 2 (http://www.gnu.org/licenses/gpl.html) See COPYING file
 * @authors See COPYRIGHT file
 */

/**
 * Fichier DAO de la table SQL history.
 */

//
// Fichier généré automatiquement le 22-04-2010.
//


/**
 * Classe d'objet virtuel de la table SQL history
 */
class historyVO
{
	/**#@+
	 * @access public
	 */
	public $id;
	public $nature;
	public $context;
	public $upd;
	/**#@-*/
}

/**
 * Classe d'abstraction de la base de données de la table history
 */
class historyDAO extends DAO
{
	/**
	 * Constructeur
	 *
	 * <p>Appelle le constructeur de la classe mère DAO en lui passant le nom de la classe.
	 * Renseigne aussi le tableau rights des droits.
	 * </p>
	 */
	public function __construct()
	{
		parent::__construct("history", true);
		$this->rights = array();
	}
}