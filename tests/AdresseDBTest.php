<?php

use PHPUnit\Framework\TestCase;

require_once "Constantes.php";
require_once "metier/Adresse.php";
require_once "PDO/AdresseDB.php";

class AdresseDBTest extends TestCase
{

    /**
     * @var AdresseDB
     */
    protected $adresse;
    protected $pdodb;
    protected $last_id;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp(): void
    {
        //parametre de connexion à la bae de donnée
        $strConnection = Constantes::TYPE . ':host=' . Constantes::HOST . ';dbname=' . Constantes::BASE;
        $arrExtraParam = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
        $this->pdodb = new PDO($strConnection, Constantes::USER, Constantes::PASSWORD, $arrExtraParam); //Ligne 3; Instancie la connexion
        $this->pdodb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        //parametre de connexion à la bae de donnée
        $strConnection = Constantes::TYPE . ':host=' . Constantes::HOST . ';dbname=' . Constantes::BASE;
        $arrExtraParam = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
	    $this->pdodb = new PDO($strConnection,
		Constantes::USER,
		Constantes::PASSWORD,
		$arrExtraParam);       //Ligne 3; Instancie la connexion
        $this->pdodb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->adresse = new AdresseDB($this->pdodb);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown(): void
    {
    }

    /**
     * @covers AdresseDB::ajout
     * @todo   Implement testAjout().
     */
    public function testAjout()
    {
        $a = new Adresse(99, 32, "rue Jean moulin", 44000, "Nantes");
        //insertion en bdd
        $robid = 6517; // rob
        $this->adresse->ajout($a, $robid);
        $id = $this->adresse->lastInsertId();
        $adr = $this->adresse->selectAdresse($id);
        $this->assertEquals($a->getNumero(), $adr->getNumero());
        $this->assertEquals($a->getRue(), $adr->getRue());
        $this->assertEquals($a->getCodePostal(), $adr->getCodePostal());
        $this->assertEquals($a->getVille(), $adr->getVille());
        $this->adresse->suppression($adr);
    }

    /**
     * @covers AdresseDB::suppression
     * @todo   Implement testSuppression().
     */
    public function testSuppression()
    {
        $a = new Adresse(99, 32, "rue Jean moulin", 44000, "Nantes");
        //insertion en bdd
        $robid = 6517; // rob
        $this->adresse->ajout($a, $robid);
        $id = $this->adresse->lastInsertId();
        $adr = $this->adresse->selectAdresse($id);
        $this->adresse->suppression($adr);
        $res = false;
        try {
            $adr = $this->adresse->selectAdresse($id);
        } catch (Exception $e)
        {
            $res = true;
        }
        $this->assertTrue($res);
    }

    /**
     * @covers AdresseDB::update
     * @todo   Implement testUpdate().
     */
    public function testUpdate()
    {
        $numero = 4444;
        $a = new Adresse(99, 32, "rue Jean moulin", 44000, "Nantes");
        $robid = 6517; // rob
        $this->adresse->ajout($a, $robid);
        $a->setNumero($numero);
        $id = $this->adresse->lastInsertId();
        $a->setId($id);
        $this->adresse->update($a);
        $adr = $this->adresse->selectAdresse($id);
        $this->adresse->suppression($adr);
        $this->assertEquals($adr->getNumero(), $numero);
    }

    /**
     * @covers AdresseDB::selectAll
     * @todo   Implement testSelectAll().
     */
    public function testSelectAll()
    {
        $a = new Adresse(99, 32, "rue Jean moulin", 44000, "Nantes");
        $robid = 6517; // rob
        $this->adresse->ajout($a, $robid);
        $id = $this->adresse->lastInsertId();
        $a->setId($id);
        //
        $res = $this->adresse->selectAll();
        $i = 0;
        $ok = false;
        foreach ($res as $key => $value) {
            $i++;
        }
        if ($i != 0)
            $ok = true;
        $this->adresse->suppression($a);
        $this->assertTrue($ok);
    }

    /**
     * @covers AdresseDB::selectAdresse
     * @todo   Implement testSelectAdresse().
     */
    public function testSelectIdAdresse()
    {
        $a = new Adresse(99, 32, "rue Jean moulin", 44000, "Nantes");
        $robid = 6517; // rob
        $this->adresse->ajout($a, $robid);
        $id = $this->adresse->lastInsertId();
        $a->setId($id);
        $adr = $this->adresse->selectAdresse($id);
        $this->adresse->suppression($a);
        $this->assertEquals($a->getNumero(), $adr->getNumero());
        $this->assertEquals($a->getRue(), $adr->getRue());
        $this->assertEquals($a->getCodePostal(), $adr->getCodePostal());
        $this->assertEquals($a->getVille(), $adr->getVille());
    }
}
