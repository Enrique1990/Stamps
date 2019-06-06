<?php

use Phalcon\Mvc\Controller;

class HomeController extends Controller
{
	public function indexAction()
	{
		// mosrar lista de stamps
		$stamps=Stamps::find();
        $this->view->stamps = $stamps;
	}

	public function addAction()
	{
	}

	public function submitAction()
	{
        //get variables from the post 
		$year=$this->request->get('year');
		$name=$this->request->get('name');
        $description=$this->request->get('description');
        $width=$this->request->get('width');
		$heigth=$this->request->get('heigth');

		// upload the image
		$ext = strtolower(pathinfo($_FILES["picture"]["name"], PATHINFO_EXTENSION));
		$filename = md5(rand() . $name) . ".$ext";
		copy($_FILES["picture"]["tmp_name"], "images/$filename");

        //save the new picture in the db
		$stamps=new Stamps();
		$stamps->name=$name;
        $stamps->year=$year;
        $stamps->picture=$filename;
        $stamps->description=$description;
        $stamps->width=$width;
        $stamps->heigth=$heigth;
		$stamps->save();

        //redirect to the listStamp
		$this->response->redirect('/home');
	}

	public function deleteAction()
	{
		// get variables from POST
		$id=$this->request->get('id');
		
		// validate no fields are empty
		if(empty($id)) {
			die("The user selected does not exist");
		}

		// delete the user from the DB
		$stamps=Stamps::findFirst($id);
		$stamps->delete();

		// redirect to user list
		$this->response->redirect('/home');
	}

	public function increaseAction()
	{
		// catch params from the get
		$id = $this->request->get('id');

		//search the picture
		$stamps=Stamps::findFirst($id);
		$stamps->quantity+=1;	
		$stamps->save();

		// redirect to login
		$this->response->redirect('home');
	}

	public function editAction()
	{
		// get ID to edit
		$id = $this->request->get('id');

		// get the user from the database
		$stamps = Stamps::findFirst($id);

		// send data to the view
		$this->view->stamps = $stamps;
	}

	public function editsubmitAction()
	{
		// get ID to edit
		$id = $this->request->get('id');
		$year=$this->request->get('year');
		$name=$this->request->get('name');
        $description=$this->request->get('description');
        $width=$this->request->get('width');
		$heigth=$this->request->get('heigth');

		// get the user from the database
		$stamps = Stamps::findFirst($id);
		$stamps->name=$name;
        $stamps->year=$year;
        $stamps->description=$description;
        $stamps->width=$width;
        $stamps->heigth=$heigth;
		$stamps->save();

		// redirect
		
		//TODO
		$this->response->redirect('home');
	}
}
