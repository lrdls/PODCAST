<?php
header('Access-Control-Allow-Origin: *');

defined('BASEPATH') OR exit('No direct script access allowed');

class Site extends CI_Controller {

private $data = NULL;

	public function getModel () {

		$this->load->model('eps_model');

		$data['title'] = $this->eps_model->getDataTitle();
        $data['articles'] = $this->eps_model->getDataArticles();
        $data['slogan'] = $this->eps_model->getDataSlogan();
        $data['rubriques'] = $this->eps_model->getDataRubriques();
        $data['rubriquesId'] = $this->eps_model->getDataRubriquesId();
        $data['liens'] = $this->eps_model->getDataLiens();
        $data['documents'] = $this->eps_model->getDataDocuments();
        $data['users'] = $this->eps_model->getAuthors();
        $data['usersLiens'] = $this->eps_model->getAuthorsLiens();

        return $data;

	}

// 	public function header () {

// 		// $data["title"] = "Et Puis Soudain";

// 		$this->load->helper('url');

//         $this->load->model('eps_model');

// 		$data['title'] = $this->eps_model->getDataTitle();
//         $data['articles'] = $this->eps_model->getDataArticles();
//         $data['slogan'] = $this->eps_model->getDataSlogan();
//         $data['rubriques'] = $this->eps_model->getDataRubriques();
//         $data['rubriquesId'] = $this->eps_model->getDataRubriquesId();
//         $data['liens'] = $this->eps_model->getDataLiens();
//         $data['documents'] = $this->eps_model->getDataDocuments();


// 		//$this ->load ->view('common/header', $data);
//         $this->load->view('site/index_bdd',$data);
// 		//$this ->load ->view('common/footer', $data);

// // $this->view_data['index_bdd'] = 'homebdd';
// // $this->load->view('site/index',$this->view_data);



// 	}



	public function index () {

		//$this->load->controller('model');
		$data = $this->getModel();


		// $data["title"] = "Et Puis Soudain";


        //$this->load->model('eps_model');

		// $data['title'] = $this->eps_model->getDataTitle();
  //       $data['articles'] = $this->eps_model->getDataArticles();
  //       $data['slogan'] = $this->eps_model->getDataSlogan();
  //       $data['rubriques'] = $this->eps_model->getDataRubriques();
  //       $data['rubriquesId'] = $this->eps_model->getDataRubriquesId();
  //       $data['liens'] = $this->eps_model->getDataLiens();
  //       $data['documents'] = $this->eps_model->getDataDocuments();
  //       $data['users'] = $this->eps_model->getAuthors();
  //       $data['usersLiens'] = $this->eps_model->getAuthorsLiens();


		$this ->load ->view('common/header', $data);
		$this ->load ->view('site/index_bdd', $data);




        // $this->load->view('site/index_bdd',$data);



		//$this ->load ->view('site/index_bdd', $data);
		$this ->load ->view('common/footer', $data);

	}

	public function index_bdd () {

		// $data["title"] = "Et Puis Soudain";


		$this->load->helper('url');

        //$this->load->model('eps_model');
        $data = $this->getModel();

		// $data['title'] = $this->eps_model->getDataTitle();
  //       $data['articles'] = $this->eps_model->getDataArticles();
  //       $data['slogan'] = $this->eps_model->getDataSlogan();
  //       $data['rubriques'] = $this->eps_model->getDataRubriques();
  //       $data['rubriquesId'] = $this->eps_model->getDataRubriquesId();
  //       $data['liens'] = $this->eps_model->getDataLiens();
  //       $data['documents'] = $this->eps_model->getDataDocuments();
  //       $data['users'] = $this->eps_model->getAuthors();
  //       $data['usersLiens'] = $this->eps_model->getAuthorsLiens();


		//$this ->load ->view('common/header', $data);
        $this->load->view('site/index_bdd',$data);
		//$this ->load ->view('common/footer', $data);

// $this->view_data['index_bdd'] = 'homebdd';
// $this->load->view('site/index',$this->view_data);



	}


	// public function eps_model()
	// {

 //        // echo "toto";
 //        // $this->load->view('add2');
 //        //$this->load->model('eps_model');
 //        //$data = $this->eps_model->getData();
 //        //print_r($data);
 //        // $this->load->view('add2');

 //        //$data['totos'] = $this->eps_model->getData();
 //        // $this->load->view('add2',$data);

 //        $this->load->model('eps_model');


 //        $data['articles'] = $this->eps_model->getDataArticles();
 //        $data['slogan'] = $this->eps_model->getDataSlogan();
 //        $data['rubriques'] = $this->eps_model->getDataRubriques();
 //        $data['rubriquesId'] = $this->eps_model->getDataRubriquesId();
 //        $data['liens'] = $this->eps_model->getDataLiens();
 //        $data['documents'] = $this->eps_model->getDataDocuments();

 //        $this->load->view('index_bdd',$data);

	// }

}