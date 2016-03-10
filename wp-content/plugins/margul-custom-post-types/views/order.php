<div class="row">
	<div class="col-xs-12" >
		<h3>You're almost finished!</h3>
		<div class="text-review">
			But first, let's review:
			<div class="text-nested">
				<br>
				<?php echo Order::boardToText($_POST['boards']); ?>
				<br>
			</div>
			<div>
			<form class="bb-form" action="" method="POST">
			</div>
			Is there anything more specific you'd like to add?
			<div class="full-width noresize">
				<textarea class="full-width" name="custComm" rows="4">
				</textarea>
			</div>
		</div>
		<div>
			<div>
				<font size="15"><b>Shipping Details:</b></font>	
				<br>
				<font size="1"><i>* denotes required fields.</i></font>
			</div>
			<br>
				<div class="form-group noresize <?php echo (in_array('custName', $objData->errors)) ? 'has-error' : ''; ?>">
					Full Name*:
					<input type="text" name="custName" class="right thirdwidth" value="<?php echo ($objData->error) ? $objData->input['custName'] : ''; ?>">
				</div>
				<div class="form-group noresize <?php echo (in_array('custAddr', $objData->errors)) ? 'has-error' : ''; ?>">
					Address*:
					<input type="text" name="custAddr" class="right thirdwidth" value="<?php echo ($objData->error) ? $objData->input['custAddr'] : ''; ?>">
				</div>
				<div class="form-group noresize">
					Address (line 2):
					<input type="text" name="custAddr2" class="right thirdwidth" value="<?php echo ($objData->error) ? $objData->input['custAddr2'] : ''; ?>">
				</div>
				<div class="form-group noresize <?php echo (in_array('custProv', $objData->errors)) ? 'has-error' : ''; ?>">
						Province*:
						<select name="custProv" class="form-control board-func right thirdwidth" data-id="0" value="<?php echo ($objData->error) ? $objData->input['custProv'] : ''; ?>">
			        		<option value="df">===Select Province===</option>
			        		<option value="al">Alberta</option>
			        		<option value="bc">British Columbia</option>
			        		<option value="on">Ontario</option>
			        		<option value="qb">Quebec</option>
			        		<option value="ma">Manitoba</option>
			        		<option value="nb">New Brunswick</option>
			        		<option value="ns">Nova Scotia</option>
			        		<option value="sa">Saskatchewan</option>
			        		<option value="pe">Prince Edward Island</option>
			        		<option value="nl">Newfoundland and Labrador</option>
			        	</select>
				</div>
				<div class="form-group noresize <?php echo (in_array('custCity', $objData->errors)) ? 'has-error' : ''; ?>">
					City*:
					<input type="text" name="custCity"  class="right thirdwidth" value="<?php echo ($objData->error) ? $objData->input['custCity'] : ''; ?>">
				</div>
				<div class="form-group noresize <?php echo (in_array('custNum', $objData->errors)) ? 'has-error' : ''; ?>">
					Phone Number*:
					<input type="text" name="custNum" class="right thirdwidth" value="<?php echo ($objData->error) ? $objData->input['custCustNum'] : ''; ?>">
				</div>
				<div class="form-group noresize <?php echo (in_array('custCode', $objData->errors)) ? 'has-error' : ''; ?>">
					Postal Code*:
					<input type="text" name="custCode" class="right thirdwidth" max="6" value="<?php echo ($objData->error) ? $objData->input['custCode'] : ''; ?>">
				</div>
				<div class="col-xs-12">
					<input type="hidden" name="board" value="<?php json_encode($_POST['boards']); ?>"/>
				</div>
				<?php if($objData->error) { ?>
                    <div class="alert alert-danger" role="alert"><strong>You seem to be missing something:</strong><br><?php echo implode('<br>', $objData->msg); ?></div>
                <?php } ?>
				<div class="buttons">
				<br>
					<input type="submit" class="btn btn-continue full-width left" value="Checkout">
					<input type="button" id="return" class="btn btn-return full-width right" value="Return">
 				</div>		
			</form>
		</div>
	</div>
</div>