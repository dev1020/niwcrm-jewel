<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception Exception */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = $name;
?>


    <div class="error-page">
					<h2 class="headline text-info"><?= Html::encode($this->title) ?></h2>
					<?php if($this->title == 'Not Found (#404)'){?>
						<div class="error-content">
                            <h3><span class="fa fa-warning text-danger"></span> Oops! Page not found.</h3>
                            <p>
                                We could not find the page you were looking for. 
                                Meanwhile, you may <a class="text-info" href='<?= Url::base()?>/site/index'>return to dashboard</a> 
                            </p>
                            
                        </div><!-- /.error-content -->
					<?php }elseif($this->title == 'Forbidden (#403)'){?>
						<div class="error-content">
                            <h3><span class="fa fa-warning text-danger"></span> You Are not Authorised to be here.</h3>
                           
							<p>
							   Please contact us if you think this is a server error. Thank you.
                                Meanwhile, you may <a class="text-info" href='<?= Url::base()?>/site/index'>return to dashboard</a> 
                            </p>
                            
                        </div><!-- /.error-content -->
						
                        <?php }?>
                        
                    </div>


