<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller
{
	private array $AUTH_RESPONSE;
	private int $AUTH_USER;
	private string $REQUEST_METHOD;

	public function __construct()
	{
		parent::__construct();

		$this->load->library('auth');
		$this->REQUEST_METHOD = $_SERVER['REQUEST_METHOD'];

		$this->AUTH_RESPONSE = $this->auth->doAuthentication("AUTHORIZATION");

		if((int)$this->AUTH_RESPONSE['status'] !== 0) {

			$this->AUTH_USER = $this->AUTH_RESPONSE['user_id'];

		}else{
            $response = $this->AUTH_RESPONSE;
            $this->core->outputResponse($response, $response['http_response_code']);
        }

        $_POST = [];
        $this->finput = json_decode(file_get_contents("php://input"), true) ?? [];
        $this->form_validation->set_data($this->finput) ?? [];
	}

    public function index() {
        $this->user();
    }

	//user info api processing
	public function user()
	{
		switch($this->REQUEST_METHOD){
			case "GET":

                $response = $this->fetchUser();

				break;

			case "PUT":

                $response = $this->updateUser();

				break;

            case "DELETE":

                $response = $this->deleteUser();

                break;
			default :

				$response = [
					'http_response_code' => 400, 'status' => 0,
					'message' => 'Invalid request method'
				];

		}

		$this->core->outputResponse($response, $response['http_response_code']);
	}

	private function fetchUser()
	{
		$response = ['http_response_code' => 404, 'status' => 0, 'message' => 'Data not found'];

		$this->db->where('id', $this->AUTH_USER);
		if($this->db->get('user')->num_rows() > 0){

			$this->db->select('id,name,username');
			$this->db->where('id', $this->AUTH_USER);
			$this->db->where('status', 1);

			$response = [
				'http_response_code' => 200, 'status' => 1,
				'message' => 'User data fetched successfully',
				'userData' => $this->db->get('user')->result()
			];
		}

		return $response;
	}

	private function updateUser()
	{
		$response = ['http_response_code' => 500, 'status' => 0, 'message' => 'Error occurred while requesting'];

		$this->form_validation->set_rules('name', 'Name', 'required');
		$this->form_validation->set_rules('username', 'Username', 'required');
		if ($this->form_validation->run() === FALSE) {

			$response = ['http_response_code' => 403 , 'status' => 0, 'message' => strip_tags(validation_errors())];

		} else {

			$pass_process_completed = TRUE;
			$data = [
				'name' => filter($this->finput['name']),
				'username' => filter($this->finput['username'])
			];

			if(!empty($this->finput['new_password'])){

                if(empty($this->finput['old_password'])){
                    return ['http_response_code' => 403 , 'status' => 0, 'message' => "Old password must not be empty"];
                }

                $userData = $this->auth->getUserData(filter($this->finput['username']), 'username');

                $password_hash = $userData->password;

				if(password_verify(filter($this->finput['old_password']), $password_hash)){

					$data['password'] = password_hash(filter($this->finput['new_password']), PASSWORD_BCRYPT);

				}else{

					$response = [
						'http_response_code' => 403, 'status' => 0,
						'message' => 'Old password did not match'
					];

					$pass_process_completed = FALSE;

				}

			}

			if($pass_process_completed){

				$this->db->where('id', $this->AUTH_USER);
				if($this->db->update('user', $data)){

					$response = [
						'http_response_code' => 200, 'status' => 1,
						'message' => 'User data updated successfully'
					];

				}

			}

		}

		return $response;
	}

	private function deleteUser()
	{
        $response = ['http_response_code' => 500, 'status' => 0, 'message' => 'Error occurred while requesting'];

        $this->db->trans_start();
        $this->db->delete('user_login', ['user_id' => $this->AUTH_USER]);
        $this->db->delete('user', ['id' => $this->AUTH_USER]);
        $this->db->trans_complete();

        if($this->db->trans_status() === TRUE){
            $response = [
                'http_response_code' => 200,
                'status' => 1,
                'message' => 'User deleted successfully'
            ];
        }

		return $response;
	}

}
