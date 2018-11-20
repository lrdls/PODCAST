<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Eps_model extends CI_Model {



	public function getDataTitle()
	{
	$this->load->database();
	$q = $this->db->query("SELECT * FROM spip_meta WHERE nom = 'nom_site'");
	return $q->result_array();
	}


	public function getDataArticles()
	{


	// return ['a'=> 'res a', 'b'=> 'res b'];
	
// 	return [['firstname'=> 'res a', 'secondname'=> 'res b'],
// 	['firstname'=> 'res a2', 'secondname'=> 'res b2'],
// 	['firstname'=> 'res a3', 'secondname'=> 'res b3'],

// ];

	$this->load->database();
	//$q = $this->db->query("SELECT * FROM spip_articles ORDER BY id_article");
	$q = $this->db->query("SELECT * FROM spip_articles  WHERE statut = 'publie' OR statut = 'prepa' ORDER BY date DESC");
	// $result = $q->result();
	return $q->result_array();
	// echo "<pre>";
	// print_r($result);
	}

///////////////////////////////////////////


	public function getDataSlogan()
	{

	$this->load->database();
	$q = $this->db->query("SELECT * FROM spip_meta WHERE nom = 'slogan_site'");
	return $q->result_array();

	}

////////////////////////////////////////////////////

	public function getDataRubriques()
	{

	$this->load->database();
	$q = $this->db->query("SELECT * FROM spip_rubriques WHERE statut = 'publie' OR statut = 'prepa'");
	return $q->result_array();

	}

	public function getDataRubriquesId()
	{

	$this->load->database();
	$q = $this->db->query("SELECT id_rubrique, titre FROM spip_rubriques WHERE statut = 'publie' OR statut = 'prepa'");
	return $q->result_array();

	}

////////////////////////////////////////////////////

	public function getDataLiens()
	{

	$this->load->database();
	$q = $this->db->query("SELECT * FROM spip_documents_liens");
	return $q->result_array();

	}

////////////////////////////////////////////////////

	public function getDataDocuments()
	{

	$this->load->database();
	$q = $this->db->query("SELECT id_document, date, fichier, extension FROM spip_documents  WHERE extension = 'mp3'");
	return $q->result_array();

	}

	public function getAuthors()
	{

	$this->load->database();
	$q = $this->db->query("SELECT * FROM spip_auteurs");
	return $q->result_array();

	}

	public function getAuthorsLiens()
	{

	$this->load->database();
	$q = $this->db->query("SELECT * FROM spip_auteurs_liens");
	return $q->result_array();

	}


}