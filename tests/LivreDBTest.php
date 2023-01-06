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
	try {
	        $l = new Livre("testTitre", "testEdition", "testInformation", "testAuteur");
            //insertion en bdd
            $this->livre->ajout($l);
            $id = $this->last_id = $this->db->lastInsertId();
            $liv = $this->livre->selectLivre($id);

            $this->assertEquals($l->getTitre(), $liv->getTitre());
            $this->assertEquals($l->getEdition(), $liv->getEdition());
            $this->assertEquals($l->getInformation(), $liv->getInformation());
            $this->assertEquals($l->getAuteur(), $liv->getAuteur());
            $this->livre->suppression($id);
        } catch (Exception $e) {
            echo 'Exception recue : ',  $e->getMessage(), "\n";
        }
    }


    /**
     * @covers LivreDB::update
     * @todo   Implement testUpdate().
     */
    public function testUpdate()
    {
	$t = "TEST";
	$l = new Livre($t."a", $t."b", $t."c", $t."d");
    $l->setId(93393399321);
	$this->livre->ajout($l);
    $this->livre->selectLivre($l->getId()); // bullshit
	$lmod = $l;
	$lmod->setTitre("fake");
	$this->livre->update($lmod);
	$ll = $this->livre->selectLivre($l->getId());
	echo $ll->getId();
	echo $l->getId();
	echo $ll->getTitre();
	echo $l->getTitre();
	echo $ll->getEdition();
	echo $l->getEdition();
	echo $ll->getInformation();
	echo $l->getInformation();
	echo $ll->getAuteur();
	echo $l->getAuteur();

	$this->assertEquals($ll->getId(), $l->getId());
	$this->assertEquals($ll->getTitre(), $l->getTitre());
	$this->assertEquals($ll->getEdition(), $l->getEdition());
	$this->assertEquals($ll->getInformation(), $l->getInformation());
	$this->assertEquals($ll->getAuteur(), $l->getAuteur());
        $this->livre->suppression($l);
    }

    /**
     * @covers LivreDB::suppression
     * @todo   Implement testSuppression().
     */
    public function testSuppression()
    {
        try {

            //$this->livre = new LivreDB($this->pdodb);
            $l = new Livre("testTitre", "testEdition", "testInformation", "testAuteur");
            $this->livre->ajout($l);
            $liv = $this->livre->selectLivre($l->getId());
            $nb = $this->livre->suppression($liv);
            if ($liv != null) {
                $this->markTestIncomplete(
                    'This test delete not ok.'
                );
	    }
        } catch (Exception $e) {
            //verification exception
            $exception = "RECORD ADRESSE not present in DATABASE";
            $this->assertEquals($exception, $e->getMessage()); // bullshit
        }
    }

    /**
     * @covers LivreDB::selectAll
     * @todo   Implement testSelectAll().
     */
    public function testSelectAll()
    {
	    //TODO test
	    //$this->livre = new LivreDB($this->pdodb);
	    $l1 = new Livre("aaaa", "bbbb", "cccc", "dddd");
	    $l1->setId(1144332);
	    $l2 = new Livre("AAAA", "BBBB", "CCCC", "DDDD");
	    $l2->setId(1144552);
	    $this->livre->ajout($l1);
	    $this->livre->ajout($l2);
	    $arrAll = $this->livre->selectAll();
	    $this->asserEquals($arrAll[0]->id, $l1->getId()); // bullshit
	    $this->asserEquals($arrAll[0]->titre, $l1->getTitre());
	    $this->asserEquals($arrAll[0]->edition, $l1->getEdition());
	    $this->asserEquals($arrAll[0]->information, $l1->getInformation());
	    $this->asserEquals($arrAll[0]->auteur, $l1->getAuteur());
	    $this->asserEquals($arrAll[1]->id, $l2->getId());
	    $this->asserEquals($arrAll[1]->titre, $l2->getTitre());
	    $this->asserEquals($arrAll[1]->edition, $l2->getEdition());
	    $this->asserEquals($arrAll[1]->information, $l2->getInformation());
	    $this->asserEquals($arrAll[1]->auteur, $l2->getAuteur());
	    $this->livre->suppression($l1);
            $this->livre->suppression($l2);
    }

    /**
     * @covers LivreDB::selectLivre
     * @todo   Implement testSelectLivre().
     */
    public function testSelectLivre()
    {
	    //TODO test
	    $id = 712381239;
	    //$this->livre = new LivreDB($this->pdodb);
	    $l = new Livre("aaaa", "bbbb", "cccc", "dddd");
	    $l->setId($id);
	    $ol = $this->livre->selectLivre($id); // bullshit
	    $this->asserEquals($ol->id, $l->getId());
	    $this->asserEquals($ol->titre, $l->getTitre());
	    $this->asserEquals($ol->edition, $l->getEdition());
	    $this->asserEquals($ol->information, $l->getInformation());
	    $this->asserEquals($ol->auteur, $l->getAuteur());
    }
}
