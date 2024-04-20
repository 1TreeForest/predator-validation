<?php
/**
 * LODEL - Logiciel d'Édition ÉLectronique.
 * @license GPL 2 (http://www.gnu.org/licenses/gpl.html) See COPYING file
 * @authors See COPYRIGHT file
 */

/**
 * Fichier DAO de la table SQL entries.
 */

//
// Fichier généré automatiquement le 20-01-2009.
//


/**
 * Classe d'objet virtuel de la table SQL entries
 *
 */
class entriesVO 
{
	/**#@+
	 * @access public
	 */
	public $id;
	public $idparent;
	public $g_name;
	public $sortkey;
	public $lang;
	public $idtype;
	public $rank;
	public $status;
	public $upd;
	/**#@-*/
}

/**
 * Classe d'abstraction de la base de données de la table entries
 *
 */
class entriesDAO extends DAO 
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
		parent::__construct("entries", true);
		$this->rights = array('write'=>LEVEL_REDACTOR, 'protect'=>LEVEL_REDACTOR);
	}

}