<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Task extends CI_Controller
{
	private array $AUTH_RESPONSE;
	private int $AUTH_USER;
	private string $REQUEST_METHOD;
    private $finput;

	public function __construct()
	{
		parent::__construct();

		$this->load->library('auth');
		$this->REQUEST_METHOD = $_SERVER['REQUEST_METHOD'];

		$this->AUTH_RESPONSE = $this->auth->doAuthentication("AUTHORIZATION");

		if((int)$this->AUTH_RESPONSE['status'] === 1) {
			$this->AUTH_USER = $this->AUTH_RESPONSE['payload']['uid'];

		}else{
            $response = $this->AUTH_RESPONSE;
            $this->core->outputResponse($response, $response['http_response_code']);
        }

        $_POST = [];
        $this->finput = json_decode(file_get_contents("php://input"), true) ?? [];
	}

    public function index()
    {
        $this->task();
    }

	public function task($id = '')
	{
        $this->form_validation->set_data(array_merge($this->finput, ['id' => $id])) ?? [];

		switch($this->REQUEST_METHOD){
			case "GET":

                $response = $this->fetch($id);

				break;

            case "POST":

                $response = $this->add();

                break;

			case "PUT":

                $response = $this->update($id);

				break;

            case "DELETE":

                $response = $this->delete($id);

                break;
			default :

				$response = [
					'http_response_code' => 400, 'status' => 0,
					'message' => 'Invalid request method'
				];

		}

		$this->core->outputResponse($response, $response['http_response_code']);
	}

	private function fetch($id)
	{
		$response = ['http_response_code' => 200, 'status' => 0, 'message' => 'Data not found'];

        !empty($id) ? $this->db->where('id', $id) : "";
		$this->db->where('user_id', $this->AUTH_USER);
		if($this->db->get('task')->num_rows() > 0){

            !empty($id) ? $this->db->where('id', $id) : "";
			$this->db->where('user_id', $this->AUTH_USER);

			$response = [
				'http_response_code' => 200, 'status' => 1,
				'message' => 'Tasks fetched successfully',
				'userData' => $this->db->get('task')->result()
			];
		}

		return $response;
	}

    private function add()
    {
        $response = ['http_response_code' => 500, 'status' => 0, 'message' => 'Error occurred while requesting'];

        $this->form_validation->set_rules('title', 'Title', 'required', ['title']);
        $this->form_validation->set_rules('status', 'Status', 'required', ['status']);

        if ($this->form_validation->run() === FALSE) {

            $response = ['http_response_code' => 403 , 'status' => 0, 'message' => strip_tags(validation_errors())];

        } else {

            $data = [
                'user_id' => $this->AUTH_USER,
                'title' => filter($this->finput['title']),
                'description' => filter($this->finput['description'] ?? ""),
                'status' => filter($this->finput['status'])
            ];

            if($this->db->insert('task', $data)){

                $response = [
                    'http_response_code' => 200, 'status' => 1,
                    'message' => 'Task added successfully'
                ];

            }

        }

        return $response;
    }

	private function update($id)
	{
		$response = ['http_response_code' => 500, 'status' => 0, 'message' => 'Error occurred while requesting'];

        $this->form_validation->set_rules('id', 'ID', 'required');

        if ($this->form_validation->run() === FALSE) {

			$response = ['http_response_code' => 403 , 'status' => 0, 'message' => strip_tags(validation_errors())];

		} else {

            $data = [
                'title' => filter($this->finput['title'] ?? ""),
                'description' => filter($this->finput['description'] ?? ""),
                'status' => filter($this->finput['status'] ?? "")
            ];

            if($this->db->update('task', $data, ['user_id' => $this->AUTH_USER, 'id' => filter($id)])){

                $response = [
                    'http_response_code' => 200, 'status' => 1,
                    'message' => 'Task updated successfully'
                ];

            }

		}

		return $response;
	}

	private function delete($id)
	{
        $response = ['http_response_code' => 500, 'status' => 0, 'message' => 'Error occurred while requesting'];

        $this->form_validation->set_data(["id" => $id]);
        $this->form_validation->set_rules('id', 'ID', 'required');

        if ($this->form_validation->run() === FALSE) {

            $response = ['http_response_code' => 403 , 'status' => 0, 'message' => strip_tags(validation_errors())];

        } else {

            if($this->db->delete('task', ['user_id' => $this->AUTH_USER, 'id' => filter($id)])){

                $response = [
                    'http_response_code' => 200, 'status' => 1,
                    'message' => 'Task deleted successfully'
                ];

            }

        }

        return $response;
	}

}
