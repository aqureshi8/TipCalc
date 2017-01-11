<!DOCTYPE html>
<html>
	<head>
		<style type="text/css">
			body {
				background-color: lightblue;
			}
			.tipCalc {
				background-color: white;
				width: 300px;
				border-style: solid;
				border-color: black;
				margin-left: auto;
				margin-right: auto;
				padding-left: auto;
				padding-right: auto;
				padding-bottom: 20px;
			}
			h1 {
				text-align: center;
≈			}
			.error {
				color: red;
				padding-left: 20px;
			}

			.bill {
				padding-left: 20px;
			}
			.tip {
				width: 50px;
			}
			.AnswerBox {
				width: 75%;
				border-style: solid;
				border-color: black;
				padding-left: 20px;
				margin-right: auto;
				margin-left: auto;
				margin-top: 20px;
				color: blue;
			}

			.submit {
				text-align: center;
			}
		</style>
	</head>
	<body>
		<?php
			$tipPercent= "10"; //Variable to hold the amount of tip to give
			$subtotal= ""; //Variable to hold bill cost
			$customTip=""; //Variable that holds custom tip

			$firstClick = true; //True if user has not hit submit for first time
			$tipErr = false; //Bad tip percentage?
			$billErr = false; //Bad bill cost?

			//Method called by Submit button
			if($_SERVER["REQUEST_METHOD"] == "POST") {

				$firstClick = false; //submit button has been pressed

				//Check if valid value for tipPercent
				if(empty($_POST["tip"])) {
					//INVALID: the value for tip was empty

					$tipErr = true; //ERROR: tip error
				}
				else {
					//the value for tip was not empty
					$tipPercent = $_POST["tip"]; //set value for tip percent

					//check if custom tip percentage
					if($tipPercent==="other") {
						//user wants to use custom tip percentage
						
						//check if given good custom tip value
						if(empty($_POST["customTip"])) {
							//INVALID: no custom tip value was given
							
							$customTip = '0'; //set custom tip to default ('0')
							$tipErr = true; //ERROR: tip error
						}
						else {
							//custom tip value was given

							$customTip = $_POST["customTip"]; //set value for custom tip

							//check if valid custom tip
							//check if custom tip is made up of numbers
							if(is_numeric($customTip)) {
								//custom tip is a number

								//check if custom tip is greater than 0
								if($customTip <= 0) {
									//INVALID: custom tip is not greater than 0

									$tipErr = true; //ERROR: tip Error
								}
							}
							else {
								//INVALID: custom tip contained characters other than numbers

								$customTip = '0'; //set custom tip to default ('0')
								$tipErr = true; //ERROR: tip error
							}

						}
					}
				}

				//Check if valid value for subtotal
				if(empty($_POST["bill"])) {
					//INVALID: subtotal was empty 

					$subtotal="0"; //set subtotal to default value ('0')
					$billErr = true; //ERROR: bill error
				}
				else {
					//subtotal recieved a value

					$subtotal = $_POST["bill"]; //set value for subtotal

					//check if subtotal value was valid
					if(is_numeric($subtotal)) {
						//subtotal is a number

						//check if subtotal is a number greater than 0
						if($subtotal <= 0) {
							//INVALID: subtotal is not greater than 0

							$billErr = true;//ERROR: bill error
						}
					}
					else {
						//INVALID: subtotal contains characters other than numbers

						$subtotal = "0";//set subtotal to default value ('0')
						$billErr = true;//ERROR: bill error
					}
				}

				//Check if any errors
				if(!$tipErr && !$billErr) {
					//No Errors

					//check if custom tip selected
					if($tipPercent === 'other') {
						//use custom tip

						$tip = number_format($customTip * 0.01 * $subtotal, 2, '.','');
						$total = number_format($tip + $subtotal, 2, '.', '');
					}
					else {
						//use tip percent
						
						$tip = number_format($tipPercent * 0.01 * $subtotal, 2, '.','');
						$total = number_format($tip + $subtotal, 2, '.', '');
					}
				}
			}
		?>

		<div class="tipCalc">
			<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
				<h1>Tip Calculator</h1>
				<div class="<?php if($billErr) {echo 'error';} else {echo 'bill';} ?>">
					<p>Bill subtotal: $ <input type="text" name="bill" value="<?php echo $subtotal ?>"></p>
				</div>
				<div class="<?php if($tipErr) {echo 'error';} else {echo 'bill';} ?>">
					<p>Tip Percentage:</p>
					<?php for ($i=0; $i < 3; $i++) { 
						$value = ($i * 5) + 10; ?>
						<input id="<?php echo $value; ?>" type="radio" name="tip" value="<?php echo $value; ?>" <?php if(isset($tipPercent) && $tipPercent==$value) { echo "checked"; } ?>><label for="<?php echo $value; ?>"><?php echo ' ' . $value . '%'; ?></label>
					<?php } ?>
					<div>
						<input id="other" type="radio" name="tip" value="other" <?php if(isset($tipPercent) && $tipPercent=="other") { echo "checked"; } ?>><label for='other'><?php echo " Custom: " ?></label><input class="tip" type="text" name="customTip" value="<?php echo $customTip ?>"><?php echo "%" ?>
					</div>
				</div>
				<br>
				<div class="submit">
					<input type="submit" name="submit" value="Submit">
				</div>
				<?php
				if(!($tipErr) && !($billErr) && !($firstClick)) {
					echo "<div class='AnswerBox'>";
					echo "<p>Tip: $$tip</p>";
					echo "<p>Total: $$total</p>";
					echo "</div>";
				}
				?>
			</form>
		</div>
	</body>
</html>