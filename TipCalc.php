<!DOCTYPE html>
<html>
	<head>
		<style type="text/css">
			.error {
				color: brown;
			}

			.bill {
				color: blue;
			}
		</style>
	</head>
	<body>

		<?php
			$tipPercent= "";
			$subtotal= "";

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
					$tip = number_format($tipPercent * 0.01 * $subtotal, 2, '.','');
					$total = number_format($tip + $subtotal, 2, '.', '');
				}
			}
		?>

		<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
			<h1>Tip Calculator</h1>
			<div class="<?php if($billErr) {echo 'error';} else {echo 'bill';} ?>">
				<p>Bill subtotal: $ <input type="text" name="bill" value="<?php echo $subtotal ?>"></p>
			</div>
			<div class="<?php if($tipErr) {echo 'error';} else {echo 'bill';} ?>">
				<?php for ($i=0; $i < 3; $i++) { 
					$value = ($i * 5) + 10; ?>
					<input type="radio" name="tip" value="<?php echo $value; ?>" <?php if(isset($tipPercent) && $tipPercent==$value) { echo "checked"; } ?>><?php echo $value . '%'; ?>
				<?php } ?>
			</div>
			<br>
			<input type="submit" name="submit" value="Submit">

			<?php
			if(!($tipErr) && !($billErr) && !($firstClick)) {
				echo "<p>Tip: $$tip</p>";
				echo "<p>Total: $$total</p>";
			}
			?>
		</form>
	</body>
</html>