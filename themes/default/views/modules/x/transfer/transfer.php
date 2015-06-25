
	<form class="form-fix">
		<br/><h2 class="form-signin-heading">Transfer Rekening</h2><br/>
		<div class="form-group">
			<label for="input1">Nomor Referensi</label>
			<input type="text" class="form-control" id="input1" placeholder="<?php echo date("Ymdhis").rand(1,100); ?>" readonly >
		</div>
		<div class="form-group">
			<label for="input1">No Rekening Pengirim</label>
			<input type="text" class="form-control" id="input1" placeholder="">
		</div>
		<div class="form-group">
			<label for="input1">No Rekening Penerima</label>
			<input type="text" class="form-control" id="input1" placeholder="">
		</div>
		<div class="form-group">
			<label for="input1">Nominal Transfer</label>
			<input type="text" class="form-control" id="input1" placeholder="">
		</div>
		<button class="btn btn-lg btn-primary btn-block" type="submit">Transfer</button>
	</form>
		