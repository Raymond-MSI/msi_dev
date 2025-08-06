<?php
function upload_file($FolderName, $PrefixName)
{
	global $DB;
	$sFolderTarget = $FolderName . '/';
	$sSubFolders = explode("/",$sFolderTarget);
	$xFolder = "";
	for($i=0;$i<count($sSubFolders);$i++) {
		if($i==0) {
			$xFolder .= $sSubFolders[$i];
		} else {
			$xFolder .= '/'.$sSubFolders[$i];
		}
		if($sSubFolders[$i]!="..") {
			if(!(is_dir($xFolder))) {
				mkdir($xFolder, 0777, true);
				// $file = 'media/index.php';
				// $newfile = $xFolder . '/index.php';
				// if (!copy($file, $newfile)) {
				// 	echo "failed to copy $file...\n";
				// }
			} 
		}
	}
	?>
	<script>
		var PrefixName = "<?php echo $PrefixName; ?>";
		var FolderTarget = "<?php echo $sFolderTarget; ?>";
        if(PrefixName=="")
        {
            delCookie("PrefixName");
        } else
        {
            setCookie("PrefixName", PrefixName)
        }
        document.cookie = "FolderTarget = " + FolderTarget;
        document.cookie = "PrefixName = " + PrefixName;
    </script>
	<div class="card shadow mb-4">
		<div class="card-header">
			Upload File
		</div>
		<!-- Card Body -->
		<div class="card-body">
			<div class="row mb-3">
				<div class="col-lg-1">
					<button type="button" class="btn btn-primary" name="btn_upload" id="btn_upload" data-bs-toggle="modal" data-bs-target="#fileupload" >Upload File</button>
				</div>
			</div>
			<div class="row mb-3">
				<div class="col-lg-12">
					<div id="fileList"></div>
				</div>
				<div class="col-lg-12">
					<?php
					$d = dir($sFolderTarget);
					?>
					<table class="table table-sm table-hover">
						<thead>
							<tr>
							<!-- <th scope="col">#</th> -->
							<th></th>
							<th scope="col">Nama File</th>
							<th scope="col">Size</th>
							<th scope="col">Modified</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$i=0;
							while (false !== ($entry = $d->read())) {
								// if($entry!='.' && $entry!='..' && $entry!='index.php') {
								if($entry!='.' && $entry!='..') {
									$fstat = stat($sFolderTarget.$entry);
									?>
									<tr>
									<!-- <th scope="row"><?php echo $i+1; ?></th> -->
									<td>
										<?php 
										if(is_file($sFolderTarget . "/" . $entry))
										{
											echo '<i class="fa fa-file"></i>';
										} else
										{
											echo '<i class="fa-regular fa-folder"></i>';
										}
										?>
									</td>
									<td>
										<!-- <a href="<?php echo $sFolderTarget.$entry; ?>" target="_blank" class="text-body"><?php echo $entry; ?></a> -->
										<?php echo $entry; ?>
									</td>
									<td class="text-center">
									<?php
									if($fstat['size']<1024) {
										echo number_format($fstat['size'],2).' B'; 
									} elseif($fstat['size']<(1024*1024)) {
										echo number_format($fstat['size']/1024,2) . ' KB';
									} elseif($fstat['size']<(1024*1024*1024)) {
										echo number_format($fstat['size']/(1024*1024),2) . ' MB';
									} elseif($fstat['size']<(1024*1024*1024*1024)) {
										echo number_format($fstat['size']/(1024*1024*1024),2) . ' GB';
									}
									?>
									</td>
									<td><?php echo date('d-M-Y G:i:s',$fstat['mtime']); ?></td>
									</tr>
									<?php
									$i++;
								}
							}
							if($i==0) {
								?>
								<tr><td colspan="4">No Files available.</td></tr>
								<?php
							}
							?>
						</tbody>
					</table>
					<?php
					$d->close();
					?>
				</div>
			</div>
		</div>
	</div>

	<!-- Modal -->
	<div class="modal fade" id="fileupload" tabindex="-1" aria-labelledby="saveLabel" aria-hidden="true">
		<div class="modal-dialog modal-lg">
			<div class="modal-content">
				<div class="modal-header">
					<h5 class="modal-title" id="saveLabel"><b>Upload File</b></h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
				</div>
				<div class="modal-body">
					<div class="row mb-3">
						<link href="components/modules/upload/upload.css" rel="stylesheet" type="text/css" />
						<form id="upload_form" enctype="multipart/form-data" method="post" action="components/modules/upload/upload.php">
							<div>
								<div>
									<label for="image_file">Please select image file</label>
								</div>
								<div>
									<input type="file" name="image_file" id="image_file" onchange="fileSelected();" />
								</div>
							</div>
							<div>
								<input type="button" value="Upload" onclick="startUploading()" />
							</div>
							<div id="fileinfo">
								<div id="filename"></div>
								<div id="filesize"></div>
								<div id="filetype"></div>
								<div id="filedim"></div>
							</div>
							<div id="error">You should select valid image files only!</div>
							<div id="error2">An error occurred while uploading the file</div>
							<div id="abort">The upload has been canceled by the user or the browser dropped the connection</div>
							<div id="warnsize">Your file is very big. We can't accept it. Please select more small file</div>
							<div id="progress_info">
								<div id="progress"></div>
								<div id="progress_percent">&nbsp;</div>
								<div class="clear_both"></div>
								<div>
									<div id="speed">&nbsp;</div>
									<div id="remaining">&nbsp;</div>
									<div id="b_transfered">&nbsp;</div>
									<div class="clear_both"></div>
								</div>
								<div id="upload_response"></div>
							</div>
						</form>
						<img id="preview" />
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	<?php
}
?>
