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
			$tipPercent= "10";
			$subtotal= "";
			$customTip="";

			$firstClick = true;
			$tipErr = false;
			$billErr = false;

			if($_SERVER["REQUEST_METHOD"] == "POST") {
				$firstClick = false;
				if(empty($_POST["tip"])) {
					$tipErr = true;
				}
				else {
					$tipPercent = $_POST["tip"];
					if($tipPercent==="other") {
						if(empty($_POST["customTip"])) {
							$customTip = '0';
							$tipErr = true;
						}
						else {
							$customTip = $_POST["customTip"];
							if(is_numeric($customTip)) {
								if($customTip <= 0) {
									$tipErr = true;
								}
							}
							else {
								$customTip = '0';
								$tipErr = true;
							}

						}
					}
				}

				if(empty($_POST["bill"])) {
					$subtotal="0";
					$billErr = true;
				}
				else {
					$subtotal = $_POST["bill"];
					if(is_numeric($subtotal)) {
						if($subtotal <= 0) {
							$billErr = true;
						}
					}
					else {
						$subtotal = "0";
						$billErr = true;
					}
				}

				if(!$tipErr && !$billErr) {
					if($tipPercent === 'other') {
						$tip = number_format($customTip * 0.01 * $subtotal, 2, '.','');
						$total = number_format($tip + $subtotal, 2, '.', '');
					}
					else {
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