<div class="container py-4">

	<div class="row">
		<div class="col">
			<div class="blog-posts single-post">
				<?=$this->session->flashdata('pesan')?>
				<?php foreach ($query as $key): ?>

					<article class="post post-large blog-single-post border-0 m-0 p-0">
						<div class="post-image ml-0 d-flex justify-content-center">
							<?php if ($key->type == 2) { ?>

								<img src="<?= base_url(); ?>themes/uploads/video/<?php echo $key->filename; ?>" class="img-fluid" alt="" />

							<?php } else { ?>
								
								<iframe width="500" height="340"
								src="<?= base_url(); ?>themes/uploads/video/<?php echo $key->filename; ?>">
							</iframe>
						<?php } ?>
					</div>
					<hr>

					<div class="post-content ml-0">

						<h2 class="font-weight-bold text-color-primary"><?php echo $key->title; ?></h2>

						<div class="post-meta">
							<span><i class="far fa-user"></i> By <?php echo $key->uploader; ?> </span>
							<span>
								<a onclick="javascript:savelike(<?php echo $key->id;?>);">
									<i class="far fa-thumbs-up" style="color: #00B297FF"></i> 
									<span id="like_<?php echo $key->id;?>">
										<?php if($key->likesbantu > 0){echo $key->likesbantu.' Likes';}else{echo 'Like';} ?>
									</span></a>
								</span>
								<span><i class="far fa-comments"></i> <?php echo $key->komen; ?> Komentar</span>
							</div>
							<span class="text-color-primary">Deskripsi</span>
							<p class="text-justify"><em><?php echo $key->description; ?></em></p>
							<hr>

							<div id="comments" class="post-block mt-3 post-comments">
								<h4 class="mb-3">Komentar (<?php echo $key->komen; ?>)</h4>

								<?php foreach ($key->listkomen as $key2): ?>
									<ul class="comments">
										<li>
											<div class="comment">
												<div class="img-thumbnail img-thumbnail-no-borders d-none d-sm-block">
													<?php if ($key2->foto == null || $key2->foto == '') { ?>
														<img class="avatar" alt="" src="<?= base_url(); ?>themes/uploads/profile/unnamed.png">
													<?php } else { ?>
														<img class="avatar" alt="" src="<?= base_url(); ?>themes/uploads/profile/<?php echo $key2->foto; ?>">
													<?php } ?>
												</div>
												<div class="comment-block">
													<div class="comment-arrow"></div>
													<span class="comment-by">
														<strong><?php echo $key2->nama; ?></strong>
														<?php if ($sesion == $key2->komen_username) { ?>
															<span class="float-right">
																<span> <a href="javascript:void(0)" onclick="delete_komen(<?=$key2->idkomen;?>)" ><i class="fas fa-trash"></i> Hapus</a></span>
															</span>
														<?php } ?>
													</span>
													<p><?php echo $key2->komentar; ?></p>
													<span class="date float-right"><?php echo $key2->waktu; ?></span>
												</div>
											</div>	
										</li>
									</ul>
								<?php endforeach; ?>

							</div>

							<?php
							if ($sesion != 'kosong') {
								?>
								<div class="container mt-3">
									<div class="row">
										<div class="col-lg-1"></div>
										<div class="col-lg-11">
											<?php echo form_open(base_url(uri_string()), array('class' => 'form-horizontal', 'id' => 'form_submit')); ?>
											<div class="form-group">
												<?php
												echo form_input('comment', (isset($post['comment'])) ? $post['comment'] : '', array('class' => 'form-control', 'placeholder' => 'tulis komentar'));
												?>
											</div>
											<div class="form-group">
												<a href="<?php echo base_url('site/vid'); ?>" name="Kembali" class="btn btn-default float-right ml-2">Kembali</a>
												<?php echo form_submit('Submit', 'Kirim Komentar', array('class' => 'btn btn-primary float-right')); ?>
											</div>
											<?php echo form_close(); ?>
										</div>
									</div>
								</div>

							<?php } ?>

						</article>

					<?php endforeach; ?>

				</div>
			</div>
		</div>

	</div>
	<hr>

	<script type="text/javascript">
		function savelike(video_id)
		{
			$.ajax({
				type: "POST",
				url: "<?php echo base_url('site/savelikes');?>",
				data: "Video_id="+video_id,
				success: function (response) {
					$("#like_"+video_id).html(response+" Likes");

				}
			});
		}

		function delete_komen(idkomen)
		{
			if(confirm('Yakin ingin menghapus komentar ini ?'))
			{
				$.ajax({
					url : "<?php echo base_url('site/ajax_delete_komen')?>/"+idkomen,
					type: "POST",
					dataType: "JSON",
					success: function(data)
					{
						$('#modal_form').modal('hide');
						location.reload();
					},
					error: function (jqXHR, textStatus, errorThrown)
					{
						alert('Gagal menghapus data');
					}
				});
			}
		}

	</script>