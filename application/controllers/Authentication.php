<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Authentication extends CI_Controller {
	private array $AUTH_RESPONSE;
	private $REQUEST_METHOD;
    
    private $finput;

	public function __construct()
	{
		parent::__construct();

		$this->load->library('auth');
		$this->REQUEST_METHOD = $_SERVER['REQUEST_METHOD'];

        $_POST = [];
        $this->finput = json_decode(file_get_contents("php://input"), true) ?? [];
        $this->form_validation->set_data($this->finput) ?? [];
	}

	public function index()
	{
		$this->processUserAccess();
	}

    public function not_found()
    {
        $response = [
            'http_response_code' => 400, 'status' => 0,
            'message' => 'Page not found'
        ];

        $this->core->outputResponse($response, $response['http_response_code']);
    }

    public function refresh_token()
    {
        switch ($this->REQUEST_METHOD) {
            case "GET":

                $response = $this->auth->refresh_token();

                break;
            default:

                $response = [
                    'http_response_code' => 400, 'status' => 0,
                    'message' => 'Invalid request method'
                ];

        }

        $this->core->outputResponse($response, $response['http_response_code']);
    }

	public function processUserAccess()
	{
		switch ($this->REQUEST_METHOD) {
			case "POST":

                $response = $this->processSignIn();

				break;
			case "DELETE":

                $response = $this->processSignOut();

				break;
			default:

				$response = [
					'http_response_code' => 400, 'status' => 0,
					'message' => 'Invalid request method'
				];

		}

		$this->core->outputResponse($response, $response['http_response_code']);
	}

	public function auth()
	{
		$response = $this->auth->doAuthentication();

		$this->core->outputResponse($response, $response['http_response_code']);
	}

	private function processSignIn() : array
    {
		$this->form_validation->set_rules('username', 'Username', 'required');
		$this->form_validation->set_rules('password', 'Password', 'required');

		if ($this->form_validation->run() === FALSE) {

			$response = array(
				'http_response_code' => 400,
				'status' => 0,
				'message' => strip_tags(validation_errors())
			);

		} else {

			$AuthResponse = $this->auth->processSignIn("authenticateUser" , $this->finput);

			if ($AuthResponse['status'] === 1) {

				$response = $this->auth->processSignIn('login', ['user_id' => $AuthResponse['user_id']]);

			} else {

				$response = array(
					'http_response_code' => $AuthResponse['http_response_code'],
					'status' => 0, 'message' => $AuthResponse['message']
				);

			}

		}

		return $response;
	}

	private function processSignOut() : array
	{
		$response = $this->auth->doAuthentication();

		if((int)$response['status'] !== 0){

			$response = $this->auth->processSignOut($response['user_id']);

		}

		return $response;
	}

	public function processSignUp()
	{
        switch ($this->REQUEST_METHOD) {
            case "POST":

                $this->form_validation->set_rules('name', 'Name', 'required');
                $this->form_validation->set_rules('username', 'Username', 'required');
                $this->form_validation->set_rules('password', 'Password', 'required|min_length[8]');

                if ($this->form_validation->run() === FALSE) {

                    $response = array(
                        'http_response_code' => 403,
                        'status' => 0,
                        'message' => strip_tags(validation_errors())
                    );

                } else {

                    $response = $this->auth->processSignup($this->finput);

                }

                break;

            default:

                $response = [
                    'http_response_code' => 400, 'status' => 0,
                    'message' => 'Invalid request method'
                ];

        }

		//Output json response
		$this->core->outputResponse($response, $response['http_response_code']);
	}

}
