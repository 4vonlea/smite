<?php
class Upload_video extends Admin_Controller
{
	public function index()
	{
		$this->load->model('Upload_video_m');
		$this->load->helper("form");
		$this->layout->render('upload_video');
	}

	public function save()
	{
		$this->load->model('Upload_video_m');
		$id = $this->input->post('id');
		$data = $this->input->post('data');

		if ($id) {
			$model = $this->Upload_video_m->findOne($id);
		} else {
			$model = new Upload_video_m();
		}
		$statusUpload = true;
		$uploadError = "";

		if (isset($_FILES['file'])) {
			$statusUpload = $this->handlingFile("file");
			$uploadData =  $this->upload->data();
			$uploadError =  $this->upload->display_errors("", "");
			$data['filename'] = $uploadData['file_name'];
			if ($data['type'] == Upload_video_m::TYPE_VIDEO && $uploadData['is_image']) {
				$statusUpload = false;
				$uploadError = "File uploaded not match with type field selected";
				unlink($uploadData['full_path']);
			} elseif ($data['type'] == Upload_video_m::TYPE_IMAGE && !$uploadData['is_image']) {
				$statusUpload = false;
				$uploadError = "File uploaded not match with type field selected";
				unlink($uploadData['full_path']);
			}
		} else if ($model && $id) {
			$data['filename'] = $model->filename;
		}

		if ($this->Upload_video_m->validate($data) && $statusUpload) {
			if ($this->input->post("video_thumb")) {
				list($type, $dataImage) = explode(';', $_POST['video_thumb']);
				list(, $dataImage)      = explode(',', $dataImage);
				$dataImage = base64_decode($dataImage);
				if (!file_exists(Upload_video_m::PATH . "thumbs")) {
					mkdir(Upload_video_m::PATH . "thumbs");
				}
				file_put_contents(Upload_video_m::PATH . "thumbs/" . $data['filename'] . ".png", $dataImage);
			}
			$model->setAttributes($data);
			$return['status'] = $model->save();
			$return['message'] = "Data has been saved succesfully";
		} else {
			$return['status'] = false;
			$return['validation'] = $this->Upload_video_m->getErrors();
			if ($uploadError)
				$return['validation']['filename'] = $uploadError;
			elseif (isset($uploadData['full_path'])) {
				unlink($uploadData['full_path']);
			}
		}
		$return['data'] = $model->toArray();
		$this->output
			->set_content_type("application/json")
			->_display(json_encode($return));
	}

	public function delete_file()
	{
		$this->load->model('Upload_video_m');

		$id = $this->input->post('id');
		$index = $this->input->post('index');
		$model = $this->Upload_video_m->findOne($id);
		$file = $this->input->post("file");
		if ($model) {
			$tempFile = json_decode($model->filename, true) ?? [];
			if (file_exist(Upload_video_m::PATH . $file)) {
				unlink(Upload_video_m::PATH . $file);
			}
			array_splice($tempFile, $index, 1);
			$model->filename = json_encode($tempFile);
			$model->save();
		}
		$this->output
			->set_content_type("application/json")
			->_display(json_encode(['status' => true]));
	}

	public function append_file()
	{
		$this->load->model('Upload_video_m');

		$id = $this->input->post('id');
		$index = $this->input->post('index');
		$model = $this->Upload_video_m->findOne($id);
		$response = ['status' => false, "message" => "Data tidak ditemukan di database"];
		if ($model) {
			$response['status'] = true;
			$tempFile = json_decode($model->filename, true) ?? [];
			if (isset($_FILES['file'])) {
				$statusUpload = $this->handlingFile("file", "{$id}/");
				$uploadData =  $this->upload->data();
				$uploadError =  $this->upload->display_errors("", "");
				$filename = $uploadData['file_name'];
				if (!$uploadData['is_image']) {
					$statusUpload = false;
					$uploadError = "File uploaded not match with type field selected";
					unlink($uploadData['full_path']);
				}
				if ($statusUpload) {
					$tempFile[$index] = "{$id}/" . $filename;
				}
				$uploadData['path'] = "{$id}/" . $filename;
				$response['status'] = $statusUpload;
				$response['message'] = $uploadError;
				$response['data'] = $uploadData;
			} else {
				$tempFile = json_decode($model->filename) ?? [];
				$tempFile[$index] = $this->input->post("file");
				$response['data']['path'] = $this->input->post("file");
			}
			$model->filename = json_encode($tempFile);
			$model->save();
		}
		$this->output
			->set_content_type("application/json")
			->_display(json_encode($response));
	}

	public function download_report()
	{
		$this->load->library('Exporter');
		$exporter = new Exporter();

		$this->load->model('Upload_video_m');

		$data = $this->Upload_video_m->downloadReport();

		$exporter->setData($data);
		$exporter->setTitle("Report Upload Video dan Gambar");
		$exporter->setFilename("Report Upload Video dan Gambar");
		$exporter->asExcel();
	}

	public function grid()
	{
		$this->load->model('Upload_video_m');

		$grid = $this->Upload_video_m->gridData($this->input->get());
		$this->output
			->set_content_type("application/json")
			->_display(json_encode($grid));
	}

	public function detail($id)
	{
		$this->load->model('Upload_video_m');
		$data = $this->Upload_video_m->findDetail($id);
		$this->output
			->set_content_type("application/json")
			->_display(json_encode($data));
	}

	/**
	 * @param $name
	 * @return boolean
	 */
	protected function handlingFile($name, $additionalPath = "")
	{
		$config['upload_path'] = Upload_video_m::PATH . $additionalPath;
		if (!file_exists(Upload_video_m::PATH)) {
			mkdir(Upload_video_m::PATH);
		}
		if ($additionalPath && !file_exists($config['upload_path'])) {
			mkdir($config['upload_path']);
		}
		$config['allowed_types'] = 'jpg|jpeg|png|mp4|mkv';
		$config['max_size'] = 102400;
		$config['file_name'] = time();
		$this->load->library('upload', $config);
		return $this->upload->do_upload($name);
	}

	public function delete()
	{
		if ($this->input->method() != 'post')
			show_404("Page Not Found !");

		$this->load->model(["Upload_video_m"]);
		$id = $this->input->post("id");
		$message = "";
		$row = $this->Upload_video_m->findOne($id);
		$files = json_decode($row->filename) ?? $row->filename;
		$files = is_array($files) ? $files : [$files];
		foreach ($files as $file) {
			if (file_exists(Upload_video_m::PATH . $file))
				unlink(Upload_video_m::PATH . $file);
		}
		$status = $row->delete();
		if ($status == false)
			$message = "Failed to delete member, error on server !";

		$this->output
			->set_content_type("application/json")
			->_display(json_encode(['status' => $status, 'message' => $message]));
	}
}
