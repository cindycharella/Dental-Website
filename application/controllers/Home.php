<?php

class Home extends CI_Controller{

	public function __construct(){
		parent::__construct();
		$this->load->model('User_model');
		$this->load->model('Admin_model');
	
	}

	public function index(){
		$data['title'] = 'Dental Care';
		$this->load->view('templates/header', $data);
		$this->load->view('home/home');
		$this->load->view('templates/footer');
	}

	public function viewlogin(){
		$data['title'] = 'Login';
		$this->load->view('templates/header', $data);
		$this->load->view('home/index');
		$this->load->view('templates/footer');	
	}

	public function adminlogin(){
		$data['title'] = 'Admin Login';
		$this->load->view('templates/header', $data);
		$this->load->view('home/adminlogin');
		$this->load->view('templates/footer');	
	}

<<<<<<< HEAD
	public function dashboard_admin(){
		$data['pelanggan'] = $this->m_admin->tampil_data_pelanggan()->result();
		$data['pembayaran'] = $this->m_admin->tampil_data_pembayaran()->result();
		$data['service'] = $this->m_admin->tampil_data_service()->result();
        $this->load->view('dashboard_admin',$data);	
	}

=======
>>>>>>> 57376f34ca8633d86477a66585d42ffc8e28a258
	public function viewRegister(){
		$data['title'] = 'Registrasi';
		$this->load->view('templates/header', $data);
		$this->load->view('home/register');
		$this->load->view('templates/footer');	
	}

	public function viewMember(){
		$data['title'] = 'Welcome';
		$this->load->view('templates/headerMember', $data);
		$this->load->view('home/memberview');
		$this->load->view('templates/footer');
	}
	public function viewprofile(){

		$this->load->model('User_model');
		$data['title'] = 'Akun Saya';
		$data['user']=$this->User_model->getuser($this->session->userdata('email'));
		$this->load->view('templates/headerMember', $data);
		$this->load->view('home/profile');
		$this->load->view('templates/footer');
	}

	public function updateprofile(){
		$this->load->model('User_model');
		$data=$this->User_model->getuser($this->session->userdata('username_or_email'));
		foreach ($data as $key) {
			$pass=$key->password;
		}
		$content = array(
			'email'=>$this->input->post('email'),
			'firstname'=>$this->input->post('firstname'),
			'lastname'=>$this->input->post('lastname'),
			'username'=>$this->input->post('displayname'),
			'password'=>$pass,
			'no_telpon'=>$this->input->post('no_telpon')
		);
		if($this->input->post('curpass')!='' ){
			if($this->input->post('curpass')==$pass){
				if($this->input->post('newpass')!='' && $this->input->post('newpass')==$this->input->post('confirmnewpass')){
					$content['password']=$this->input->post('newpass');
					$this->User_model->update($content);
					echo "<script type='text/javascript'> alert('Success update profile') </script>";
					$this->viewprofile_detail();
				}else echo "<script type='text/javascript'> alert('password baru tidak valid') </script>";

			}else echo "<script type='text/javascript'> alert('password salah') </script>";

		}else{
			$this->User_model->update($content);
			echo "<script type='text/javascript'> alert('Success update profile') </script>";
			$this->viewprofile_detail();
		}
	}
	public function login(){

		$this->form_validation->set_rules('username_or_email', 'username_or_email', 'required');
		$this->form_validation->set_rules('password', 'password', 'required');

		$unoe = $this->input->post('username_or_email');
		$pass = $this->input->post('password');

		$this->db->from('user');
		$this->db->where(array('username' => $unoe));
		$this->db->or_where(array('email' => $unoe));
		$cek = $this->db->get();


		if($this->form_validation->run() == FALSE){
			redirect(base_url('Home/viewlogin'));
		}else if($this->User_model->validate($this->input->post('username_or_email'), $this->input->post('password')) == TRUE){
			$data = $cek->row();

			//$this->session->set_userdata('username_or_email', $this->input->post('username_or_email'));
			$datauser = array (
				'id_user' => $data->id_user,
				'email' => $data->email,
				'username' => $data->username,
				'nama' => $data->nama,
				'alamat' => $data->alamat,
				'nomor_telpon' => $data->nomor_telpon
			);

			$this->session->set_userdata($datauser);
			$this->viewMember();
		}else{
			$this->viewlogin();
		}
		
	}

	public function administratorlogin(){

		$this->form_validation->set_rules('username_or_email', 'username_or_email', 'required');
		$this->form_validation->set_rules('password', 'password', 'required');

		$unoe = $this->input->post('username_or_email');
		$pass = $this->input->post('password');

		$this->db->from('admin');
		$this->db->where(array('username' => $unoe));
		$this->db->or_where(array('email' => $unoe));
		$cek = $this->db->get();


		if($this->form_validation->run() == FALSE){
			redirect(base_url('Home/adminlogin'));
		}else if($this->Admin_model->validate($this->input->post('username_or_email'), $this->input->post('password')) == TRUE){
			$data = $cek->row();

			//$this->session->set_userdata('username_or_email', $this->input->post('username_or_email'));
			$dataadmin = array (
				'id' => $data->id,
				'username' => $data->username,
				'nama' => $data->nama

			);

			$this->session->set_userdata($dataadmin);
			$this->viewMember();
		}else{
			$this->viewlogin();
		}
		
	}

	public function register(){

		$this->form_validation->set_rules('email', 'email', 'required|is_unique[user.email]');
		$this->form_validation->set_rules('password', 'password', 'required');
		$this->form_validation->set_rules('nomor_telpon', 'nomor_telpon', 'required');

		if($this->form_validation->run() == FALSE){
			redirect(base_url());
		}else{
			$data = [
				"email" =>$this->input->post('email', TRUE),
				"password" =>$this->input->post('password', TRUE),
				"nomor_telpon" =>$this->input->post('nomor_telpon', TRUE),
			];

			$this->User_model->insert($data);
			redirect(base_url().'Home/viewlogin');
		}
	}

	public function logout(){
		$this->session->sess_destroy();
		redirect(base_url().'/Home/viewlogin');
	}
	public function deleteakun(){
		$email=$this->session->userdata('username_or_email');
		$this->User_model->delete($email);
		$this->session->sess_destroy();
		redirect(base_url().'/Home/viewlogin');
	}
<<<<<<< HEAD

	public function view_pelanggan(){
        $data['pelanggan'] = $this->admin_model->tampil_data_pelanggan()->result();
        $this->load->view('/home/dasboard_admin',$data);
	}
	
	public function view_pembayaran(){
        $data['pembayaran'] = $this->admin_model->tampil_data_pembayaran()->result();
        $this->load->view('/home/dashboard_admin',$data);
	}

	public function aksi_add_service(){
		$id_service = $this->input->post('id_service');
		$nama_service = $this->input->post('nama_service');
		$nama_dokter = $this->input->post('nama_dokter');
		$nama_pasien = $this->input->post('nama_pasien');
		$ruangan = $this->input->post('ruangan');
		$jam_operasional = $this->input->post('jam_operasional');

		$data = array(
			'id_service' => $id_service,
			'nama_service' => $nama_lengkap,
			'nama_dokter' => $nama_dokter,
			'nama_pasien' => $nama_pasien,
			'ruangan' => $ruangan,
			'jam_operasional' => $jam_operasional,
			);

		$this->admin_model->input_data($data,'service');
		redirect('home/dashboard_admin');
	}

	public function hapus_service(){
		$id_service=$this->input->post('id_service');
		$this->admin_model->hapus_service($id_service);
		redirect('home/dashboard_admin');
	}

	public function delete_all_service(){
		$this->admin_model->delete_all_service();
		redirect('home/dashboard_admin');
	}

	public function update_service(){
		//Feature prediction
		$id_service = $this->input->post('id_service');
		$nama_service = $this->input->post('nama_service');
		$nama_dokter = $this->input->post('nama_dokter');
		$nama_pasien = $this->input->post('nama_pasien');
		$ruangan = $this->input->post('ruangan');
		$jam_operasional = $this->input->post('jam_operasional');

		$data = array(
			'id_service' => $id_service,
			'nama_service' => $nama_lengkap,
			'nama_dokter' => $nama_dokter,
			'nama_pasien' => $nama_pasien,
			'ruangan' => $ruangan,
			'jam_operasional' => $jam_operasional,
		);
	
		$where = array(
			'id_service' => $id_service
		);
	
		$this->admin_model->update_data($where,$data,'service');
		redirect('home/dashboard_admin');
	}
=======
	

>>>>>>> 57376f34ca8633d86477a66585d42ffc8e28a258
}

?>