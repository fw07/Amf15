<?php $client_account =  $this->uri->segment(3); ?>
<section class="main">
	<div class="container">
	
		<div id="module_title">
			<div class="m-b-md"><h3 class="m-b-none"><?php echo $menu_title; ?></h3></div>
		</div>
	
		<form class="form-horizontal" enctype="multipart/form-data" id="" action="" method="post">
			<div class="panel panel-default">
			
				<!-- Panel Head -->
				<div class="panel-heading">
					<!-- Nav tabs -->
					<ul class="nav nav-pills">
						<li class="active"><a href="#personalinfo" data-toggle="tab">Tambah Jurnal</a></li>
					</ul>
				</div>
				
				<!-- Panel Body -->
				<div class="panel-body">
					<!-- Tab panes -->
					<div class="tab-content">
						<div class="tab-pane active" id="personalinfo">
							<?php echo validation_errors('<div class="alert alert-danger"> <button type="button" class="close" data-dismiss="alert"><i class="icon-remove"></i></button>', '</div>'); ?>
							
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Tanggal</label>
								<div class="col-sm-4">
									<?php if(!$data->tabwajib_date){ ?>
									<input type="text" name="tabwajib_date" class="form-control datepicker-input" data-date-format="yyyy-mm-dd" id="" placeholder="" value="<?php echo date('Y-m-d'); ?>">
									<?php }else{ ?>
									<input type="text" name="tabwajib_date" class="form-control datepicker-input" data-date-format="yyyy-mm-dd" id="" placeholder="" value="<?php echo set_value('tabwajib_tr_date', isset($data->tabwajib_tr_date) ? $data->tabwajib_date : ''); ?>">
									<?php } ?>
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Debet</label>
								<div class="col-sm-4">
											<select class="form-control">
												<option>1010001	KAS TELLER</option>
												<option>1010002	KAS DROPING</option>
												<option>1010003	KAS PENARIKAN TABUNGAN</option>
												<option>1010004	KAS KECIL</option>
												<option>1020000	REKENING DI BANK</option>
												<option>1030000	PIUTANG PEMBIAYAAN</option>
												<option>1040000	INVESTASI PADA KANTOR CABANG</option>
												<option>1050000	CADANGAN PENGHAPUSAN AKTIVA PRODUKTIF (CPP)</option>
												<option>1060000	ASET TETAP</option>
												<option>1070000	ASET LAIN-LAIN	</option>
												<option>2010000	SIMPANAN ANGGOTA</option>
												<option>2020000	SIMPANAN BERJANGKA</option>
												<option>2040000	HUTANG PEMBIAYAAN</option>
												<option>2050000	HUTANG LAIN-LAIN</option>
												<option>2050100	DANA AMANAH</option>
												<option>2050101	ZAKAT KARYAWAN</option>
												<option>2050102	INFAQ KARYAWAN</option>
												<option>2050103	SHADAQAH KARYAWAN</option>
												<option>2050200	TITIPAN</option>
												<option>2050201	TITIPAN ASURANSI</option>
												<option>2050202	TITIPAN DANA SEMBAKO</option>
												<option>2050203	TITIPAN DANA KURBAN</option>
												<option>2050204	TITIPAN LAINNYA</option>
												<option>2050300	HUTANG PAJAK PENGHASILAN</option>
												<option>2050400	REKENING SELISIH PEMBUKUAN</option>
												<option>2050500	REKENING SELISIH PEMBULATAN</option>
												<option>2050600	BIAYA YANG MASIH HARUS DIBAYAR LAINNYA</option>
												<option>2050700	HUTANG KE REKENING</option>
												<option>2050701	HUTANG KE REKENING RF</option>
												<option>2050702	HUTANG KE REKENING TABUNGAN</option>													
												<option>3010000	MODAL</option>
												<option>3020000	SALDO LABA/RUGI</option>

											</select>
											<br/>
											<input type="text" name="tabwajib_debet" class="form-control" id="" placeholder="" value="<?php echo set_value('tabwajib_debet', isset($data->tabwajib_tr_debet) ? $data->tabwajib_tr_debet : '0'); ?>" />
										
									</div>
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Kredit</label>
								<div class="col-sm-4">
											<select class="form-control">
												<option>1010001	KAS TELLER</option>
												<option>1010002	KAS DROPING</option>
												<option>1010003	KAS PENARIKAN TABUNGAN</option>
												<option>1010004	KAS KECIL</option>
												<option>1020000	REKENING DI BANK</option>
												<option>1030000	PIUTANG PEMBIAYAAN</option>
												<option>1040000	INVESTASI PADA KANTOR CABANG</option>
												<option>1050000	CADANGAN PENGHAPUSAN AKTIVA PRODUKTIF (CPP)</option>
												<option>1060000	ASET TETAP</option>
												<option>1070000	ASET LAIN-LAIN	</option>
												<option>2010000	SIMPANAN ANGGOTA</option>
												<option>2020000	SIMPANAN BERJANGKA</option>
												<option>2040000	HUTANG PEMBIAYAAN</option>
												<option>2050000	HUTANG LAIN-LAIN</option>
												<option>2050100	DANA AMANAH</option>
												<option>2050101	ZAKAT KARYAWAN</option>
												<option>2050102	INFAQ KARYAWAN</option>
												<option>2050103	SHADAQAH KARYAWAN</option>
												<option>2050200	TITIPAN</option>
												<option>2050201	TITIPAN ASURANSI</option>
												<option>2050202	TITIPAN DANA SEMBAKO</option>
												<option>2050203	TITIPAN DANA KURBAN</option>
												<option>2050204	TITIPAN LAINNYA</option>
												<option>2050300	HUTANG PAJAK PENGHASILAN</option>
												<option>2050400	REKENING SELISIH PEMBUKUAN</option>
												<option>2050500	REKENING SELISIH PEMBULATAN</option>
												<option>2050600	BIAYA YANG MASIH HARUS DIBAYAR LAINNYA</option>
												<option>2050700	HUTANG KE REKENING</option>
												<option>2050701	HUTANG KE REKENING RF</option>
												<option>2050702	HUTANG KE REKENING TABUNGAN</option>													
												<option>3010000	MODAL</option>
												<option>3020000	SALDO LABA/RUGI</option>

											</select>
											<br/>
											<input type="text" name="tabwajib_debet" class="form-control" id="" placeholder="" value="<?php echo set_value('tabwajib_debet', isset($data->tabwajib_tr_debet) ? $data->tabwajib_tr_debet : '0'); ?>" />
										
									</div>
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label">Remark</label>
								<div class="col-sm-4">
									<input type="text" name="tabwajib_remark" class="form-control" id="" placeholder="" value="<?php echo set_value('tabwajib_remark', isset($data->tabwajib_tr_remark) ? $data->tabwajib_tr_remark : ''); ?>" />
								</div>
							</div>
							
							
						</div>		
						
						
					</div>
					
				</div>
				
				<!-- Panel Footer -->
				<div class="panel-footer">
					<div class="form-group">
						<div class="col-sm-3 ">
							<input type="hidden" name="tabwajib_client" class="form-control" id="tabwajib_client" placeholder="" value="<?php echo set_value('tabwajib_client', isset($data->tabwajib_client) ? $data->tabwajib_client : ''); ?>">
							<input type="hidden" name="tabwajib_saldo" class="form-control" id="tabwajib_saldo" placeholder="" value="<?php echo set_value('tabwajib_saldo', isset($data->tabwajib_saldo) ? $data->tabwajib_saldo : ''); ?>">
							<input type="hidden" name="tabwajib_total_debet" class="form-control" id="tabwajib_total_debet" placeholder="" value="<?php echo set_value('tabwajib_total_debet', isset($data->tabwajib_debet) ? $data->tabwajib_debet : ''); ?>">
							<input type="hidden" name="tabwajib_total_credit" class="form-control" id="tabwajib_total_credit" placeholder="" value="<?php echo set_value('tabwajib_total_credit', isset($data->tabwajib_credit) ? $data->tabwajib_credit: ''); ?>">
							<button type="submit" class="btn btn-primary">Save Data</button>
						</div>
					</div>
				</div>
			</div>
			
			
			

			
			
			
		</form>
	</div>
</div>	