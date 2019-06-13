<?php 

$data_mata_pelajaran = $data_mapel2->result_object();


 ?>
                
                <!-- START BREADCRUMB -->
                <ul class="breadcrumb">
                    <li><a href="index.php">Dashboard</a></li>                    
                    <li class="active"><?php echo $title; ?></li>
                </ul>
                <!-- END BREADCRUMB -->
            
                <!-- PAGE CONTENT WRAPPER -->
                <div class="page-content-wrap">                
                
                    <div class="row">
                        <div class="col-md-12">

                        <?php 
                        if ($this->session->flashdata('success')) {
                            ?>
                            <div class="alert alert-success" id="autoclose"> 
                                 <?php echo $this->session->flashdata('success'); ?>
                            </div>
                            <?php
                        }elseif ($this->session->flashdata('failed')) {
                            ?>
                            <div class="alert alert-danger" id="autoclose"> 
                                 <?php echo $this->session->flashdata('failed'); ?>
                            </div>
                            <?php
                        }

                         ?>
                            <!-- START DEFAULT DATATABLE -->
                            <div class="panel panel-default">
                                <div class="panel-heading">                                
                                    <h3 class="panel-title"><?php echo $title; ?> 
                                    	<b>(<?php echo $data_mata_pelajaran[0]->subjenis_mapel; ?>, <?php echo $data_mata_pelajaran[0]->mapel ?>, TA <?php echo $data_mata_pelajaran[0]->tahun_pelajaran; ?>) <span style="color: blue"> [<?php echo strtoupper($this->input->get('type')) ?>]</span>  Semester <?php echo $data_semester->nama; ?></b>
                                    </h3>                      
                                </div>
                                <div class="panel-body">
                                    <div class="table-responsive">

                                    	<table class="table table-hover">
                                    		<thead>
											<tr>
												<th>Nama</th>
												<th>Kelas</th>
												<?php 
												foreach ($data_kd_prota->result_object() as $value) {
													?>
													<th><?php echo $value->nomor_kd; ?> <?php if($value->status_nilai == 'uts'){echo "<span class='label label-success'>UTS</span>";}else{echo "<span class='label label-info'>UAS</span>";} ?></th>
													<?php
												}
												?>
											</tr>
											</thead>
											<tbody>
											<?php
												$total_row = $data_kd_prota->num_rows();
												foreach ($hasil as $value_hasil) {
													?>
													<tr>
														<td><?php echo strtoupper($value_hasil['nama']); ?></td>
														<td>
															<?php echo $value_hasil['nama_kelas']; ?>
															<?php echo $value_hasil['nama_prodi']; ?>
															<?php echo $value_hasil['nama_rombel']; ?>
														</td>
														<?php 
														for ($i=0; $i < $total_row; $i++) { 
															?>
															<td 
                                                                <?php 
                                                                if ($value_hasil[$i] < $data_skm->nilai && $value_hasil[$i] != 0 ) {
                                                                    ?>
                                                                    style="font-weight: bold; color: orange; font-style: italic; text-decoration: underline;  ";
                                                                    <?php
                                                                }elseif($value_hasil[$i] > 100 ){
                                                                    ?>
                                                                    style="font-weight: bold; color: red; background-color: yellow; ";
                                                                    <?php
                                                                }

                                                                if ($value_hasil[$i] == 0 ) {
                                                                    ?>
                                                                    style="font-weight: bold; font-style: italic;  color: red; text-decoration: underline; ";
                                                                    <?php
                                                                }                                                                

                                                                 ?>
                                                            ><?php echo $value_hasil[$i]; ?></td>
															<?php
														}

														 ?>
														
													</tr>
													<?php
												}

												?>
											</tbody>
										</table>

                                    </div>
                                </div>
                            </div>
                            <!-- END DEFAULT DATATABLE -->


                        </div>
                    </div>                                

                </div>


