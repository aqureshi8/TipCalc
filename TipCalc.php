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
					$tip = number_format($_POST["tip"] * 0.01 * $_POST["bill"], 2, '.','');
				}

				if(empty($_POST["bill"]) || $_POST["bill"] <= 0) {
					$billErr = true;
				}
				else {
					if(!empty($_POST["tip"])) {
						$total = number_format($tip + $_POST["bill"], 2, '.', '');
					}
				}
			}
		?>

		<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
			<h1>Tip Calculator</h1>
			<div class="<?php if($billErr) {echo 'error';} else {echo 'bill';} ?>">
				<p>Bill subtotal: $ <input type="text" name="bill"></p>
			</div>
			<div class="<?php if($tipErr) {echo 'error';} else {echo 'bill';} ?>">
				<?php for ($i=0; $i < 3; $i++) { 
					$tipPercent = ($i * 5) + 10; ?>
					<input type="radio" name="tip" value="<?php echo $tipPercent; ?>"><?php echo $tipPercent . '%'; ?>
				<?php } ?>
			</div>
			<br><br>
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