<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Nilai_mapel extends CI_Controller {

	public function __construct()
	{
		parent::__construct();
		cek_session_level('superadmin');
		date_default_timezone_set('Asia/Jakarta');
		$this->page->set_base_url(base_url("superadmin/nilai_mapel"));
		$this->load->model('nilai_mapel_model');
	}

	public function index()
	{
		$get_tahun_aktif = $this->nilai_mapel_model->get_tahun_aktif()->row();
		$get_tahun = $get_tahun_aktif->id_tahun_pelajaran;
		
		$value = array(
						'get_data' 		=> $this->nilai_mapel_model->get($get_tahun),
						'semester'		=> $this->nilai_mapel_model->get_semester(),
			);
		$this->page->title('Daftar Mata Pelajaran');
		$this->page->template('superadmin-template');
		$this->page->content('content/superadmin/nilai_mapel');
		$this->page->data($value);
		$this->page->view();
	}

	public function nilai_kd($id_mapel_tahunan)
	{

		$cek = $this->nilai_mapel_model->get_mapel($id_mapel_tahunan)->num_rows();

		if ($cek > 0) {
			$type = $this->input->get('type');

			if (isset($type)) {

				if ($type == 'kognitif') {
					$val_type = 'kognitif';
					$title_type = 'PENGETAHUAN';
				}elseif ($type == 'psikomotor'){
					$val_type = 'psikomotor';
					$title_type = 'KETERAMPILAN';
				}

				$smt = $_GET['smt'];

				$data_mapel 		= $this->nilai_mapel_model->get_mapel($id_mapel_tahunan);
				$data_mapel_prota 	= $this->nilai_mapel_model->get_siswa($id_mapel_tahunan);
				$data_kd_prota 		= $this->nilai_mapel_model->get_kd($id_mapel_tahunan, $val_type, $smt);
				$data_semester 		= $this->nilai_mapel_model->get_semester_by_id($smt);
				$data_skm  			= $this->nilai_mapel_model->get_skm($data_mapel->row()->id_tahun_pelajaran, $smt);

				if ($data_mapel_prota->num_rows() < 1 ) {
					$show_table = 0;
				}else{
					$show_table = 1;
				}

				$hasil = [];
				foreach ($data_mapel_prota->result_object() as $value_mapel_prota) {
					$kd = [
							'nama' => $value_mapel_prota->nama_siswa,
							'nama_kelas' => $value_mapel_prota->nama_kelas,
							'nama_prodi' => $value_mapel_prota->nama_prodi,
							'nama_rombel' => $value_mapel_prota->nama_rombel,
						  ];
					foreach ($data_kd_prota->result_object() as $value_kd) {
						$data_nilai = $this->nilai_mapel_model->get_nilai($value_kd->id_kd_prota, $value_mapel_prota->id_riwayat_siswa);
						
						if (empty($data_nilai->result_array())) {
							$nilai = "0";
						}else{
							$nilai = $data_nilai->result_array(MYSQLI_ASSOC)[0]['nilai'];
						}
						array_push($kd, $nilai);
					}
					array_push($hasil, $kd);	
				}

				$data_value = array(
										'show_table' 		=> $show_table,
										'hasil' 			=> $hasil, 
										'data_kd_prota' 	=> $data_kd_prota,
										'data_mapel2' 		=> $data_mapel,
										'data_semester'		=> $data_semester->row(),
										'data_skm' 			=> $data_skm->row()
								   );


				$this->page->title(strtoupper($title_type));
				$this->page->template('superadmin-template');
				$this->page->content('content/superadmin/rekapitulasi-nilai-mapel');
				$this->page->data($data_value);
				$this->page->view();

			}else{
				$this->output->set_status_header('404');
	       		$this->load->view('templates/404');
			}
	
		}else{
				$this->output->set_status_header('404');
	       		$this->load->view('templates/404');
		}
		
	}


	public function nilai_ujian($id_mapel_tahunan)
	{
		$cek = $this->nilai_mapel_model->get_mapel($id_mapel_tahunan)->num_rows();

		if ($cek > 0) {

			$get_type = $_GET['type'];

			$cek_smt = $this->nilai_mapel_model->get_semester_by_id($get_type)->num_rows();

			if ($cek_smt > 0) {
				$data_mapel 		= $this->nilai_mapel_model->get_mapel($id_mapel_tahunan);
				$data_mapel_prota 	= $this->nilai_mapel_model->get_siswa($id_mapel_tahunan);
				$data_status 		= array('uts', 'uas' );
				$data_semester 		= $this->nilai_mapel_model->get_semester_by_id($get_type)->row();
				$data_skm  			= $this->nilai_mapel_model->get_skm($data_mapel->row()->id_tahun_pelajaran, $get_type);

				if ($data_mapel_prota->num_rows() < 1 ) {
					$show_table = 0;
				}else{
					$show_table = 1;
				}

				$hasil = [];
				foreach ($data_mapel_prota->result_object() as $value_mapel_prota) {
					$kd = [
							'nama' => $value_mapel_prota->nama_siswa,
							'nama_kelas' => $value_mapel_prota->nama_kelas,
							'nama_prodi' => $value_mapel_prota->nama_prodi,
							'nama_rombel' => $value_mapel_prota->nama_rombel,
						  ];
					foreach ($data_status as $value_status) {
						$data_nilai = $this->nilai_mapel_model->get_nilai_ujian($id_mapel_tahunan, $get_type, $value_status, $value_mapel_prota->id_riwayat_siswa);
						
						if (empty($data_nilai->result_array())) {
							$nilai = "0";
						}else{
							$nilai = $data_nilai->result_array(MYSQLI_ASSOC)[0]['nilai'];
						}
						array_push($kd, $nilai);
					}
					array_push($hasil, $kd);	
				}

				$data_value = array(
										'show_table' 		=> $show_table,
										'hasil' 			=> $hasil, 
										'data_status' 		=> $data_status,
										'data_mapel2' 		=> $data_mapel,
										'data_semester' 	=> $data_semester,
										'data_skm' 			=> $data_skm->row()
								   );


				$this->page->title('UJIAN');
				$this->page->template('superadmin-template');
				$this->page->content('content/superadmin/rekapitulasi-nilai-ujian');
				$this->page->data($data_value);
				$this->page->view();
			}else{
				$this->output->set_status_header('404');
	       		$this->load->view('templates/404');
			}

				
	
		}else{
				$this->output->set_status_header('404');
	       		$this->load->view('templates/404');
		}
	}


	}