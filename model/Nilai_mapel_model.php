<?php
class Nilai_mapel_model extends CI_Model
{
	
	function get($get_tahun)
	{	
		return $this->db->query("
									SELECT 
											sm.nama AS subjenis_mapel,
											m.nama AS mapel,
											k.singkatan AS kelas,
											pr.singkatan AS prodi,
											mt.id_mapel_tahunan
										FROM mapel_tahunan mt 
										LEFT JOIN mapel m ON mt.id_mapel = m.id_mapel
										LEFT JOIN kelas k ON mt.id_kelas = k.id_kelas
										LEFT JOIN prodi pr ON mt.id_prodi = pr.id_prodi
										LEFT JOIN subjenis_mapel sm ON m.id_subjenis_mapel = sm.id_subjenis_mapel
										WHERE mt.id_tahun_pelajaran = '$get_tahun'
										ORDER BY k.nama, pr.singkatan ASC
			");
	}

	function get_tahun_aktif()
	{
		return $this->db->query("
									SELECT DISTINCT(id_tahun_pelajaran) FROM status_input 

			");
	}

	function get_prota($tahun, $user)
	{
		return $this->db->query("
									SELECT * FROM prota WHERE id_tahun_pelajaran = '$tahun' AND id_user = '$user'
			");
	}

	function get_subjenis_mapel()
	{
		$this->db->order_by('nama', 'ASC');
		return $this->db->get('subjenis_mapel');
	}

	function insert($data)
	{
		return $this->db->insert('mapel', $data);
	}
	
	function get_data_by_kode($kode)
	{
		return $this->db->query("SELECT * FROM mapel WHERE kode = '$kode' ");
	}

	function get_data_by_id($id)
	{
		return $this->db->where('id_mapel', $id)->get_where('mapel');
	}

	function update($data,$id)
	{
		return $this->db->where('id_mapel', $id)->update('mapel', $data);
	}

	function delete($id)
	{
		return $this->db->where('id_mapel', $id)->delete('mapel');
	}

	//test

	function get_siswa($id_mapel_tahunan)
	{
		return $this->db->query("SELECT 
									rs.id_riwayat_siswa,
									s.nama AS nama_siswa,
									k.singkatan AS nama_kelas,
									pr.singkatan AS nama_prodi,
									r.nama AS nama_rombel,
									tp.nama AS nama_tahun
									FROM mapel_tahunan mt
								    LEFT JOIN riwayat_siswa rs ON mt.id_kelas = rs.id_kelas
								    LEFT JOIN siswa s ON rs.id_siswa = s.id_siswa
								    LEFT JOIN kelas k ON rs.id_kelas = k.id_kelas
								    LEFT JOIN prodi pr ON rs.id_prodi = pr.id_prodi
								    LEFT JOIN rombel r ON rs.id_rombel = r.id_rombel
								    LEFT JOIN tahun_pelajaran tp ON rs.id_tahun_pelajaran = tp.id_tahun_pelajaran
								    WHERE mt.id_mapel_tahunan = '$id_mapel_tahunan'
								    AND rs.id_tahun_pelajaran = mt.id_tahun_pelajaran
								    AND rs.id_prodi = mt.id_prodi
								    ORDER BY r.nama ASC, s.nama ASC"
								);
	}

	function get_kd($id_mapel_tahunan, $type, $smt)
	{
		return $this->db->query("SELECT * FROM kd_prota WHERE id_mapel_tahunan = '$id_mapel_tahunan' AND jenis_kd = '$type' AND id_semester = '$smt'
			");
	}

	function get_nilai($id_kd_prota, $id_riwayat_siswa)
	{
		return $this->db->query("SELECT nilai FROM nilai_kd_prota WHERE id_kd_prota = '$id_kd_prota' AND id_riwayat_siswa = '$id_riwayat_siswa' ");
	}

	function get_nilai_ujian($id_mapel_tahunan, $get_type, $status, $id_riwayat_siswa)
	{
		return $this->db->query("SELECT nilai FROM nilai_ujian_prota WHERE id_mapel_tahunan = '$id_mapel_tahunan' AND id_semester = '$get_type' AND status_nilai = '$status' AND id_riwayat_siswa = '$id_riwayat_siswa' ");
	}


	function get_mapel($id_mapel_tahunan)
	{
		return $this->db->query("SELECT 
									mt.id_tahun_pelajaran,
									jm.nama AS jenis_mapel,
									sm.nama AS subjenis_mapel,
									m.nama AS mapel,
									tp.nama AS tahun_pelajaran
								FROM mapel_tahunan mt
								LEFT JOIN tahun_pelajaran tp ON mt.id_tahun_pelajaran = tp.id_tahun_pelajaran
								LEFT JOIN mapel m ON mt.id_mapel = m.id_mapel
								LEFT JOIN subjenis_mapel sm ON m.id_subjenis_mapel = sm.id_subjenis_mapel
								LEFT JOIN jenis_mapel jm ON sm.id_jenis_mapel = jm.id_jenis_mapel
								WHERE mt.id_mapel_tahunan = '$id_mapel_tahunan' ");
	}

	function get_skm($id_tahun_pelajaran, $smt)
	{
		return $this->db->query("
									SELECT * FROM skm_prota sp
										WHERE id_tahun_pelajaran = '$id_tahun_pelajaran' AND id_semester = '$smt'
			");
	}

	function get_semester()
	{
		$this->db->order_by('kode', 'ASC');
		return $this->db->get('semester');
	}

	function get_semester_by_id($id_semester)
	{
		$this->db->order_by('kode', 'ASC');
		return $this->db->where('id_semester', $id_semester)->get_where('semester');
	}

}
?>