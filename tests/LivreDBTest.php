<?php

use PHPUnit\Framework\TestCase;
use WebDriver\Log;

require_once "Constantes.php";
require_once "metier/Livre.php";
require_once "PDO/LivreDB.php";


class LivreDBTest extends TestCase
{
    /**
     * @var LivreDB
     */
    protected $livre;
    protected $pdodb;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp(): void
    {
        //parametre de connexion à la bae de donnée
        $strConnection = Constantes::TYPE . ':host=' . Constantes::HOST . ';dbname=' . Constantes::BASE;
        $arrExtraParam = array(PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8");
	    $this->pdodb = new PDO($strConnection,
		Constantes::USER,
		Constantes::PASSWORD,
		$arrExtraParam);       //Ligne 3; Instancie la connexion
        $this->pdodb->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->livre = new LivreDB($this->pdodb);
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown(): void
    {
	    $this->addToAssertionCount(10);
    }

    /**
     * @covers LivreDB::ajout
     * @todo   Implement testAjout().
     */
    public function testAjout()
    {
        $l = new Livre("testTitre", "testEdition", "testInformation", "testAuteur");
        //insertion en bdd
        $this->livre->ajout($l);
        $id = $this->livre->lastInsertId();
        $liv = $this->livre->selectLivre($id);
        $this->assertEquals($l->getTitre(), $liv->getTitre());
        $this->assertEquals($l->getEdition(), $liv->getEdition());
        $this->assertEquals($l->getInformation(), $liv->getInformation());
        $this->assertEquals($l->getAuteur(), $liv->getAuteur());
        $this->livre->suppression($id);
    }


    /**
     * @covers LivreDB::update
     * @todo   Implement testUpdate().
     */
    public function testUpdate()
    {
        $titre = "Teststestestes";
        $l = new Livre("testTitre", "testEdition", "testInformation", "testAuteur");
        $this->livre->ajout($l);
        $l->setTitre($titre);
        $id = $this->livre->lastInsertId();
        $l->setId($id);
        $this->livre->update($l);
        $adr = $this->livre->selectLivre($id);
        $this->livre->suppression($adr);
        $this->assertEquals($adr->getTitre(), $titre);
    }

    /**
     * @covers LivreDB::suppression
     * @todo   Implement testSuppression().
     */
    public function testSuppression()
    {
        $l = new Livre("testTitre", "testEdition", "testInformation", "testAuteur");
        //insertion en bdd
        $this->livre->ajout($l);
        $id = $this->livre->lastInsertId();
        $liv = $this->livre->selectLivre($id);
        $this->livre->suppression($liv->getId());
        $res = false;
        try {
            $liv = $this->livre->selectLivre($id);
        } catch (Exception $e)
        {
            $res = true;
        }
        $this->assertTrue($res);
    }

    /**
     * @covers LivreDB::selectAll
     * @todo   Implement testSelectAll().
     */
    public function testSelectAll()
    {
        $l = new Livre("testTitre", "testEdition", "testInformation", "testAuteur");
        $this->livre->ajout($l);
        $id = $this->livre->lastInsertId();
        $l->setId($id);
        $res = $this->livre->selectAll();
        $i = 0;
        $ok = false;
        foreach ($res as $key => $value) {
            $i++;
        }
        if ($i != 0)
            $ok = true;
        $this->livre->suppression($id);
        $this->assertTrue($ok);
    }

    /**
     * @covers LivreDB::selectLivre
     * @todo   Implement testSelectLivre().
     */
    public function testSelectLivre()
    {
	    $l = new Livre("testTitre", "testEdition", "testInformation", "testAuteur");
        $this->livre->ajout($l);
        $id = $this->livre->lastInsertId();
        $l->setId($id);
        $liv = $this->livre->selectLivre($id);
        $this->livre->suppression($id);
        $this->assertEquals($l->getTitre(), $liv->getTitre());
        $this->assertEquals($l->getEdition(), $liv->getEdition());
        $this->assertEquals($l->getInformation(), $liv->getInformation());
        $this->assertEquals($l->getAuteur(), $liv->getAuteur());
    }
}
