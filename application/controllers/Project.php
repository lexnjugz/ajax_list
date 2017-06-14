<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Project extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->model('project_model','project');
	}

	public function index()
	{
		$data['product']= $this->project->get_product_dropdown();
		$data['client']= $this->project->get_client_dropdown();
		$data = json_decode( json_encode($data), true);
		$this->load->view('project_view', $data);
	}

	public function ajax_list()
	{

		$list = $this->project->get_datatables();
		$data = array();
		$no = $_POST['start'];
		foreach ($list as $project) {
			$no++;
			$row = array();
			$row[] = $project->project_title;
			$row[] = $project->client_name;
			$row[] = $project->product_name;
			$row[] = substr($project->project_details,0,200) . '...';

			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Add_slider" onclick="add_projSlider('."'".$project->project_Id."'".')"><i class="glyphicon glyphicon-image"></i> Add Slider img</a>';

			//add html for action
			$row[] = '<a class="btn btn-sm btn-primary" href="javascript:void(0)" title="Edit" onclick="edit_project('."'".$project->project_Id."'".')"><i class="glyphicon glyphicon-pencil"></i> Edit</a>
				  <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="delete_project('."'".$project->project_Id."'".')"><i class="glyphicon glyphicon-trash"></i> Delete</a>';

		
			$data[] = $row;
		}

		$output = array(
						"draw" => $_POST['draw'],
						"recordsTotal" => $this->project->count_all(),
						"recordsFiltered" => $this->project->count_filtered(),
						"data" => $data,
				);
		//output to json format
		echo json_encode($output);
	}

	public function ajax_edit($id)
	{
		$data = $this->project->get_by_id($id);
		echo json_encode($data);
	}

	public function ajax_add()
	{
		$data = array(
				'project_title' => $this->input->post('project_title'),
				'project_details' => $this->input->post('project_details'),
				'client_Id' => $this->input->post('client_Id'),
				'product_Id' => $this->input->post('product_Id'),
			);
		$insert = $this->project->save($data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_update()
	{
		$data = array(
				'project_title' => $this->input->post('project_title'),
				'project_details' => $this->input->post('project_details'),
				'client_Id' => $this->input->post('client_Id'),
				'product_Id' => $this->input->post('product_Id'),
			);
		$this->project->update(array('project_Id' => $this->input->post('id')), $data);
		echo json_encode(array("status" => TRUE));
	}

	public function ajax_delete($id)
	{
		$this->project->delete_by_id($id);
		echo json_encode(array("status" => TRUE));
	}

	public function project_slider($id)
	{
		$this->load->view('project_img_view', $id);
	}

	public function project_slider_upload($id)
	{
		$config['upload_path']   = FCPATH.'/project_slider_images/';
        $config['allowed_types'] = 'gif|jpg|png|ico';
        $this->load->library('upload',$config);

        if($this->upload->do_upload('userfile')){
        	$token=$this->input->post('image_token');
        	$nama=$this->upload->data('file_name');
        	$this->db->insert('project_imgs',array('project_image'=>$name,'project_Id'=>$id,'token'=>$token));
        }
	}

	//	To delete a photo
	function project_remove_image(){

		//Take token photo
		$token=$this->input->post('token');

		
		$image=$this->db->get_where('project_imgs',array('token'=>$token));


		if($image->num_rows()>0){
			$result=$image->row();
			$image_name=$result->image_name;
			if(file_exists($file=FCPATH.'/upload-foto/'.$image_name)){
				unlink($file);
			}
			$this->db->delete('project_imgs',array('token'=>$token));

		}


		echo "{}";
	}

}
