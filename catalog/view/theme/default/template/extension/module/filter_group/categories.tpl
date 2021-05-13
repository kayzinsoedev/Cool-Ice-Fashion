<?php if($categories) { ?>
	<div id="side-categories">
		<div class="list-group-item item-header" ><?= $heading_title; ?></div>
		<div class="list-group-item">
                    <?php
                    if($parent_id != 0){
                        $data = array();
                        foreach($categories as $category){ 
                            if($category['category_id'] == $parent_id){
                                $data = $category['child'];
                            }
                        }
                        $categories = $data;
                    }
                    ?>
			<?php foreach($categories as $category){ ?>
				
				<div class="side-categories-level-1">
					<div class="group">
							<div class="item level-1 <?= $category['active']; ?>" data-path="<?= $category['path']; ?>" >
								<a href="<?= $category['href']; ?>" ><?= $category['name']; ?></a>
								<?php if($category['child']){ ?>
								<div class="toggle level-1 pointer"><div class="caret"></div></div>
								<?php } ?>
							</div>
                                            
							<?php if($category['child']){ ?>
								<div class="sub level-2">
										<?php foreach($category['child'] as $child_1){ ?>
											<div class="group" >
												<div class="item level-2 <?= $child_1['active']; ?>" data-path="<?= $child_1['path']; ?>" >
													<a href="<?= $child_1['href']; ?>" class="" ><?= $child_1['name']; ?></a>
													<?php if(isset($child_1['child'])){ ?>
														<div class="toggle level-2 pointer"><div class="caret"></div></div>
													<?php } ?>
												</div>
											
											<?php if(isset($child_1['child'])){ ?>
											<div class="sub level-3">
												<?php foreach($child_1['child'] as $child_2){ ?>
													<div class="item level-3" data-path="<?= $child_2['path']; ?>" >
														<a href="<?= $child_2['href']; ?>" class="<?= $child_2['active']; ?>" ><?= $child_2['name']; ?></a>
													</div>
												<?php } ?>
											</div>
											<?php } ?>
											<!---->

											</div>
										<?php } ?>
								</div>
							<?php } ?>
							<!---->
						</div>
					</div>
					
			<?php } ?>
		</div>
	</div>
<?php } ?>