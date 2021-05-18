<?php if(!class_exists('Rain\Tpl')){exit;}?><div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="jumbotron">
                <h1 class="display-4"><?php echo htmlspecialchars( $title, ENT_COMPAT, 'UTF-8', FALSE ); ?></h1>
                <p class="lead"><?php echo htmlspecialchars( $subtitle, ENT_COMPAT, 'UTF-8', FALSE ); ?></p>
                <hr class="my-4">
                <p><?php echo htmlspecialchars( $content, ENT_COMPAT, 'UTF-8', FALSE ); ?></p>
            </div>
        </div>
    </div>
</div>