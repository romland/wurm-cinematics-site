<?php
	$indexFilename = "/tmp/examples_index.txt";
	$fileSeparator = "===================================== File Separator =====================================\r\n";
	$comments = array(
		"0010 - Hello World.txt" => "This is roughly the most simple scene you can create.",
	);

	$indexContents = "";

	//
	// If we don't have a file containing all examples yet, create one that we can reuse later.
	//
	if(file_exists($indexFilename) === false) {

		$files = array();
		$dir = opendir("examples");
		while(false != ($file = readdir($dir))) {
		        if(($file != ".") and ($file != "..") and ($file != "index.php")) {
		                $files[] = $file; // put in array.
		        }   
		}
		natsort($files);

		foreach($files as $exampleFilename) {
			$exampleContents = file_get_contents("examples/" . $exampleFilename);
			
			$exampleName = str_replace(".txt", "", substr($exampleFilename, 7));

			$indexContents .= $fileSeparator;
			$indexContents .= $exampleName . "\r\n";
			$indexContents .= $exampleFilename . "\r\n";
			$indexContents .= $exampleContents;
		}

		file_put_contents($indexFilename, $indexContents);
	}

	//
	// If we didn't just create an "index" file, load it from disk.
	//
	if(strlen($indexContents) === 0) {
		// get from cache
		$indexContents = file_get_contents($indexFilename);
	}


	//
	// Actually output the examples.
	//
	$examples = explode($fileSeparator, $indexContents);
	$odd = false;
	foreach($examples as $ex) {
		$odd = !$odd;
		if(strlen($ex) === 0) {
			continue;
		}
		$lines = explode("\r\n", $ex);
		$exampleName = $lines[0];
		$fileName = $lines[1];
		$example = "";
		for($i = 2; $i < sizeof($lines); $i++) {
			$firstChar = substr(trim($lines[$i]), 0, 1);
			if($firstChar == "#") {
				$example .= "<span style='color: #0000bb;'>" . $lines[$i] . "</span>\n";
			} else if($firstChar == "/") {
				$example .= "<span style='color: #bb0000;'>" . $lines[$i] . "</span>\n";
			} else {
				$example .= $lines[$i] . "\n";
			}
		}
?>
	<!-- an example -->
	<div class="row" style="margin-bottom: 200px; <?php if($odd) { echo "background-color: #f0f0f0;"; }?>">
		<div class="col-md-1 col-sm-3 col-xs-6">
		</div>
		<div class="col-md-6 col-sm-3 col-xs-6">
			<div class=" scrollpoint sp-effect2">
				<h3><?php echo $exampleName; ?></h3>
				<p>mods/friyas-cinematics/Cinematics/examples/<?php echo $fileName; ?></p>
<pre>
<?php echo $example; ?>
</pre>
				<p>
					<?php echo $comments[$fileName]; ?>
				</p>
			</div>
		</div>

		<div class="col-md-4 col-sm-3 col-xs-6" >
			<div class="scrollpoint sp-effect5">
				<h3>&nbsp;</h3>
				<p>
					Video or image here at some point. Donations in the form of Vimeo or Youtube links are welcome.
				</p>
			</div>
		</div>

		<div class="col-md-1 col-sm-3 col-xs-6">
		</div>
	</div>
        <!-- end of an example -->
<?php
	}
?>
